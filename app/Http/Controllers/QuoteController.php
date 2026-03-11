<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Mail\LeadReceivedMail;
use App\Models\Lead;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Setting;
use App\Support\AttributionLogger;
use App\Support\FormSpamGuard;
use App\Support\LocaleUrls;
use App\Support\TrackingEventLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class QuoteController extends Controller
{
    public function __construct(
        protected AttributionLogger $attributionLogger,
        protected TrackingEventLogger $trackingEventLogger,
        protected FormSpamGuard $formSpamGuard
    ) {
    }

    public function index(Request $request)
    {
        $lang = app()->getLocale();

        $products = Product::query()->where('is_active', true)->with('translations')->get();
        $categories = ProductCategory::query()->where('is_active', true)->with('translations')->orderBy('order')->get();

        $seoTitles = [
            'tr' => 'Teklif Al | Lunar Ambalaj',
            'en' => 'Get Quote | Lunar Packaging',
            'ru' => 'Запросить предложение | Lunar Packaging',
            'ar' => 'طلب عرض سعر | Lunar Packaging',
            'es' => 'Solicitar Cotización | Lunar Ambalaj',
        ];
        $seoDescs = [
            'tr' => 'Kategori, ürün, adet ve baskı ihtiyaçlarınızı iletin. Ekibimiz 24 saat içinde teklif ve termin bilgisiyle dönüş yapar.',
            'en' => 'Share category, product, quantity and print requirements. Our team replies with quote and lead-time details within 24 hours.',
            'ru' => 'Укажите категорию, продукт, объем и параметры печати. Команда вернется с расчетом и сроками в течение 24 часов.',
            'ar' => 'شارك الفئة والمنتج والكمية ومتطلبات الطباعة. يعود فريقنا بعرض السعر ومدة التنفيذ خلال 24 ساعة.',
            'es' => 'Comparte categoría, producto, cantidad y requisitos de impresión. Nuestro equipo responde con precio y plazo en 24 horas.',
        ];

        return view('quote.index', [
            'botGuard' => $this->formSpamGuard->issueChallenge($request, 'quote'),
            'products' => $products,
            'categories' => $categories,
            'seo' => $this->seo(
                $seoTitles[$lang] ?? $seoTitles['en'],
                $seoDescs[$lang] ?? $seoDescs['en'],
                LocaleUrls::abs(config("site.route_translations.quote.{$lang}")),
                LocaleUrls::static('quote'),
            ),
        ]);
    }

    public function store(StoreQuoteRequest $request)
    {
        $key = 'quote:' . sha1($request->ip() . $request->userAgent());
        $emailKey = 'quote:email:' . sha1(Str::lower((string) $request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($key, 5) || RateLimiter::tooManyAttempts($emailKey, 3)) {
            return back()->withErrors(['form' => __('security.too_many_requests')])->withInput();
        }

        RateLimiter::hit($key, 60);
        RateLimiter::hit($emailKey, 600);

        if (!$this->formSpamGuard->validateSubmission($request, 'quote')) {
            return back()->withErrors(['form' => __('security.bot_check_failed')])->withInput();
        }

        if ($this->formSpamGuard->isLikelySpam($request->input('message'))) {
            return back()->withErrors(['form' => __('security.spam_detected')])->withInput();
        }

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

        $successMessages = [
            'tr' => 'Teklif talebiniz alındı.',
            'en' => 'Your quote request has been received.',
            'ru' => 'Ваш запрос на предложение получен.',
            'ar' => 'تم استلام طلب عرض السعر.',
            'es' => 'Hemos recibido tu solicitud de cotización.',
        ];
        $warningTemplates = [
            'tr' => 'Min. sipariş miktarı :min adettir.',
            'en' => 'Minimum order quantity is :min units.',
            'ru' => 'Минимальный объем заказа: :min единиц.',
            'ar' => 'الحد الأدنى للطلب هو :min وحدة.',
            'es' => 'La cantidad mínima de pedido es :min unidades.',
        ];

        $warning = $quantity < $minOrder
            ? str_replace(':min', (string) $minOrder, $warningTemplates[app()->getLocale()] ?? $warningTemplates['en'])
            : null;

        $thankYouRoute = match (app()->getLocale()) {
            'tr' => 'tr.quote.thankyou',
            'en' => 'en.quote.thankyou',
            'ru' => 'ru.quote.thankyou',
            'ar' => 'ar.quote.thankyou',
            'es' => 'es.quote.thankyou',
            default => 'tr.quote.thankyou',
        };

        return redirect()->route($thankYouRoute)
            ->with('success', $successMessages[app()->getLocale()] ?? $successMessages['en'])
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
            'es' => '/es/get-quote/thank-you',
        ];
        $titleByLocale = [
            'tr' => 'Teklif Talebi Alındı | Lunar Ambalaj',
            'en' => 'Quote Request Received | Lunar Packaging',
            'ru' => 'Запрос получен | Lunar Packaging',
            'ar' => 'تم استلام الطلب | Lunar Packaging',
            'es' => 'Solicitud Recibida | Lunar Ambalaj',
        ];
        $descByLocale = [
            'tr' => 'Talebiniz başarıyla alındı. Ekibimiz en kısa sürede sizinle iletişime geçecektir.',
            'en' => 'Your request has been received. Our team will contact you shortly.',
            'ru' => 'Ваш запрос получен. Наша команда свяжется с вами в ближайшее время.',
            'ar' => 'تم استلام طلبك. سيتواصل معك فريقنا قريبًا.',
            'es' => 'Hemos recibido tu solicitud. Nuestro equipo se pondrá en contacto contigo en breve.',
        ];

        $currentPath = $pathByLocale[$lang] ?? $pathByLocale['tr'];

        return view('quote.thankyou', [
            'seo' => $this->seo(
                $titleByLocale[$lang] ?? $titleByLocale['en'],
                $descByLocale[$lang] ?? $descByLocale['en'],
                LocaleUrls::abs($currentPath),
                LocaleUrls::static('quote'),
            ),
        ]);
    }
}
