<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Mail\LeadReceivedMail;
use App\Models\Lead;
use App\Support\AttributionLogger;
use App\Support\LocaleUrls;
use App\Support\TrackingEventLogger;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function __construct(
        protected AttributionLogger $attributionLogger,
        protected TrackingEventLogger $trackingEventLogger
    ) {
    }

    public function index()
    {
        $lang = app()->getLocale();

        // SEO-optimized contact page
        $seoTitles = [
            'tr' => 'İletişim | 24 Saatte Yanıt | Lunar Ambalaj',
            'en' => 'Contact | 24h Response | Lunar Packaging',
            'ru' => 'Контакт | Ответ за 24 часа | Lunar Packaging',
            'ar' => 'اتصل بنا | رد خلال 24 ساعة | Lunar Packaging',
        ];

        $seoDescs = [
            'tr' => 'Ambalaj ürün teklifi, sipariş detayları, baskı planlama için bizimle iletişime geçin. 24 saat içinde yanıt veriyoruz. Telefon, email, WhatsApp.',
            'en' => 'Contact us for packaging quotes, order details, print planning. We respond within 24 hours. Phone, email, WhatsApp available.',
            'ru' => 'Свяжитесь с нами для получения предложений по упаковке, деталей заказа, планирования печати. Мы отвечаем в течение 24 часов. Телефон, email, WhatsApp.',
            'ar' => 'اتصل بنا للحصول على عروض أسعار التعبئة وتفاصيل الطلب وتخطيط الطباعة. نحن نرد خلال 24 ساعة. الهاتف والبريد الإلكتروني وواتساب متاحة.',
        ];

        $seoTitle = $seoTitles[$lang] ?? $seoTitles['en'];
        $seoDesc = $seoDescs[$lang] ?? $seoDescs['en'];

        return view('contact.index', [
            'seo' => $this->seo(
                $seoTitle,
                $seoDesc,
                LocaleUrls::abs(config("site.route_translations.contact.{$lang}")),
                LocaleUrls::static('contact'),
            ),
        ]);
    }

    public function store(StoreContactRequest $request)
    {
        $key = 'contact:' . sha1($request->ip() . $request->userAgent());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['form' => __('Too many requests. Please try again soon.')])->withInput();
        }

        RateLimiter::hit($key, 60);
        $attributionPayload = $this->attributionLogger->getAttributionPayload($request);

        $lead = Lead::query()->create([
            'type' => 'contact',
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone') ?: null,
            'message' => $request->input('message'),
            'meta' => [
                'locale' => app()->getLocale(),
                'source' => 'contact_form',
                'ip' => $request->ip(),
                'ua' => Str::limit((string) $request->userAgent(), 180),
                'referrer' => (string) $request->headers->get('referer'),
                'utm_source' => $attributionPayload['utm_source'] ?? null,
                'utm_medium' => $attributionPayload['utm_medium'] ?? null,
                'utm_campaign' => $attributionPayload['utm_campaign'] ?? null,
            ],
        ]);

        $this->attributionLogger->logLeadAttribution($lead, $request, [
            'lead_type' => 'contact',
        ]);
        $this->trackingEventLogger->log($request, 'lead_submit', [
            'lead_type' => 'contact',
            'product_category' => null,
            'quantity' => null,
        ], $lead);

        Mail::to(config('mail.from.address'))->send(new LeadReceivedMail($lead));

        $successMessages = [
            'tr' => 'Mesajınız alındı.',
            'en' => 'Your message has been received.',
            'ru' => 'Ваше сообщение получено.',
            'ar' => 'تم استلام رسالتك.',
        ];

        return back()->with('success', $successMessages[app()->getLocale()] ?? $successMessages['en'])
            ->with('lead_submitted', true)
            ->with('lead_type', 'contact');
    }
}
