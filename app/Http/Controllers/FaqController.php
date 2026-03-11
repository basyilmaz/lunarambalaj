<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Support\LocaleUrls;

class FaqController extends Controller
{
    public function index()
    {
        $lang = app()->getLocale();

        $faqs = Faq::query()
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('order')
            ->get();

        $entities = $faqs->map(function ($faq) use ($lang): array {
            $translation = $faq->translation($lang);

            return [
                '@type' => 'Question',
                'name' => $translation?->question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags((string) $translation?->answer),
                ],
            ];
        })->filter(static fn (array $item): bool => !empty($item['name']))->values()->all();

        $seoTitles = [
            'tr' => 'Sıkça Sorulan Sorular | MOQ, Termin, Baskı | Lunar Ambalaj',
            'en' => 'Frequently Asked Questions | MOQ, Lead Time, Print | Lunar Packaging',
            'ru' => 'Часто задаваемые вопросы | MOQ, Сроки, Печать | Lunar Packaging',
            'ar' => 'الأسئلة الشائعة | الحد الأدنى للطلب، المدة، الطباعة | Lunar Packaging',
            'es' => 'Preguntas Frecuentes | MOQ, Plazo, Impresión | Lunar Ambalaj',
        ];
        $seoDescs = [
            'tr' => 'Minimum sipariş, termin süreleri, baskı dosyaları, sevkiyat ve fiyatlandırma hakkında sık sorulan sorular.',
            'en' => 'FAQs about minimum order quantities, lead times, print files, shipping and pricing for B2B packaging orders.',
            'ru' => 'Частые вопросы о минимальном заказе, сроках, файлах печати, отгрузке и ценах для B2B-поставок.',
            'ar' => 'الأسئلة الشائعة حول الحد الأدنى للطلب والمهل وملفات الطباعة والشحن والتسعير لطلبات B2B.',
            'es' => 'Preguntas frecuentes sobre pedido mínimo, plazos, archivos de impresión, envío y precios para operaciones B2B.',
        ];

        return view('faq.index', [
            'faqs' => $faqs,
            'seo' => $this->seo(
                $seoTitles[$lang] ?? $seoTitles['en'],
                $seoDescs[$lang] ?? $seoDescs['en'],
                LocaleUrls::abs(config("site.route_translations.faq.{$lang}")),
                LocaleUrls::static('faq'),
                [[
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => $entities,
                ]],
            ),
        ]);
    }
}
