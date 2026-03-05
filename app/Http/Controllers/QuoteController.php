<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Mail\LeadReceivedMail;
use App\Models\Lead;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Setting;
use App\Support\AttributionLogger;
use App\Support\LocaleUrls;
use App\Support\TrackingEventLogger;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class QuoteController extends Controller
{
    public function __construct(
        protected AttributionLogger $attributionLogger,
        protected TrackingEventLogger $trackingEventLogger
    ) {
    }

    public function index()
    {
        $lang = app()->getLocale();

        $products = Product::query()->where('is_active', true)->with('translations')->get();
        $categories = ProductCategory::query()->where('is_active', true)->with('translations')->orderBy('order')->get();

        return view('quote.index', [
            'products' => $products,
            'categories' => $categories,
            'seo' => $this->seo(
                $lang === 'tr' ? 'Teklif Al | Lunar Ambalaj' : 'Get Quote | Lunar Packaging',
                $lang === 'tr' ? 'Projeniz için hızlı fiyat teklifi alın.' : 'Request a fast quotation for your project.',
                LocaleUrls::abs(config("site.route_translations.quote.{$lang}")),
                LocaleUrls::static('quote'),
            ),
        ]);
    }

    public function store(StoreQuoteRequest $request)
    {
        $key = 'quote:' . sha1($request->ip() . $request->userAgent());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['form' => __('Too many requests. Please try again soon.')])->withInput();
        }

        RateLimiter::hit($key, 60);
        $attributionPayload = $this->attributionLogger->getAttributionPayload($request);

        $quantity = (int) $request->input('quantity');
        $setting = Setting::query()->first();
        $minOrder = $setting?->min_order_default ?: 5000;

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('lead-attachments', 'public');
        }

        $lead = Lead::query()->create([
            'type' => 'quote',
            'name' => $request->input('name'),
            'company' => $request->input('company') ?: null,
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'message' => $request->input('message') ?: null,
            'meta' => [
                'locale' => app()->getLocale(),
                'source' => 'quote_form',
                'product_category' => $request->input('product_category'),
                'product' => $request->input('product'),
                'quantity' => $quantity,
                'print_needed' => $request->input('print_needed'),
                'wrapping_needed' => $request->input('wrapping_needed'),
                'delivery_city' => $request->input('delivery_city'),
                'attachment' => $attachmentPath,
                'ip' => $request->ip(),
                'ua' => Str::limit((string) $request->userAgent(), 180),
                'referrer' => (string) $request->headers->get('referer'),
                'utm_source' => $attributionPayload['utm_source'] ?? null,
                'utm_medium' => $attributionPayload['utm_medium'] ?? null,
                'utm_campaign' => $attributionPayload['utm_campaign'] ?? null,
            ],
        ]);

        $this->attributionLogger->logLeadAttribution($lead, $request, [
            'lead_type' => 'quote',
            'product_category' => $request->input('product_category'),
            'quantity' => $quantity,
        ]);
        $this->trackingEventLogger->log($request, 'lead_submit', [
            'lead_type' => 'quote',
            'product_category' => $request->input('product_category'),
            'quantity' => $quantity,
        ], $lead);

        Mail::to(config('mail.from.address'))->send(new LeadReceivedMail($lead));

        $message = app()->getLocale() === 'tr' ? 'Teklif talebiniz alındı.' : 'Your quote request has been received.';
        $warning = $quantity < $minOrder
            ? (app()->getLocale() === 'tr' ? 'Min. sipariş miktarı ' . $minOrder . ' adettir.' : 'Minimum order quantity is ' . $minOrder . ' units.')
            : null;

        $thankYouRoute = match (app()->getLocale()) {
            'tr' => 'tr.quote.thankyou',
            'en' => 'en.quote.thankyou',
            'ru' => 'ru.quote.thankyou',
            'ar' => 'ar.quote.thankyou',
            default => 'tr.quote.thankyou',
        };

        return redirect()->route($thankYouRoute)
            ->with('success', $message)
            ->with('warning', $warning)
            ->with('lead_submitted', true)
            ->with('lead_type', 'quote')
            ->with('lead_payload', [
                'product_category' => $request->input('product_category'),
                'quantity' => $quantity,
            ]);
    }

    public function thankyou()
    {
        $lang = app()->getLocale();
        $pathByLocale = [
            'tr' => '/teklif-al/tesekkurler',
            'en' => '/en/get-quote/thank-you',
            'ru' => '/ru/get-quote/thank-you',
            'ar' => '/ar/get-quote/thank-you',
        ];
        $currentPath = $pathByLocale[$lang] ?? $pathByLocale['tr'];

        return view('quote.thankyou', [
            'seo' => $this->seo(
                $lang === 'tr' ? 'Teklif Talebi Alındı | Lunar Ambalaj' : 'Quote Request Received | Lunar Packaging',
                $lang === 'tr' ? 'Talebiniz başarıyla alındı. Ekibimiz en kısa sürede sizinle iletişime geçecektir.' : 'Your request has been received. Our team will contact you shortly.',
                LocaleUrls::abs($currentPath),
                LocaleUrls::static('quote'),
            ),
        ]);
    }
}