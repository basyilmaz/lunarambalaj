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
        })->filter(fn (array $item): bool => ! empty($item['name']))->values()->all();

        // SEO-optimized FAQ page
        $seoTitles = [
            'tr' => 'Sıkça Sorulan Sorular | MOQ, Termin, Baskı | Lunar Ambalaj',
            'en' => 'Frequently Asked Questions | MOQ, Lead Time, Print | Lunar Packaging',
            'ru' => 'Часто задаваемые вопросы | MOQ, Сроки, Печать | Lunar Packaging',
            'ar' => 'الأسئلة الشائعة | الحد الأدنى للطلب، المهلة، الطباعة | Lunar Packaging',
        ];

        $seoDescs = [
            'tr' => 'Minimum sipariş, termin süreleri, baskı dosyaları, sevkiyat, fiyatlandırma hakkında sıkça sorulan sorular ve detaylı yanıtlar.',
            'en' => 'FAQs about minimum order quantities, lead times, print files, shipping, pricing. Detailed answers for B2B packaging orders.',
            'ru' => 'Часто задаваемые вопросы о минимальных объемах заказа, сроках, файлах печати, доставке, ценах. Подробные ответы для заказов упаковки B2B.',
            'ar' => 'الأسئلة الشائعة حول الحد الأدنى لكميات الطلب والمهل وملفات الطباعة والشحن والتسعير. إجابات مفصلة لطلبات التعبئة B2B.',
        ];

        $seoTitle = $seoTitles[$lang] ?? $seoTitles['en'];
        $seoDesc = $seoDescs[$lang] ?? $seoDescs['en'];

        return view('faq.index', [
            'faqs' => $faqs,
            'seo' => $this->seo(
                $seoTitle,
                $seoDesc,
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
