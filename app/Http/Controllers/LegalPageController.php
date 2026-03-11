<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Support\LocaleUrls;

class LegalPageController extends Controller
{
    public function kvkk()
    {
        return $this->renderLegalPage('kvkk', 'kvkk');
    }

    public function privacy()
    {
        return $this->renderLegalPage('privacy', 'privacy');
    }

    public function cookie()
    {
        return $this->renderLegalPage('cookie', 'cookie');
    }

    public function distanceSales()
    {
        return $this->renderLegalPage('distance_sales', 'distance_sales');
    }

    public function terms()
    {
        return $this->renderLegalPage('terms', 'terms');
    }

    private function renderLegalPage(string $pageKey, string $routeKey)
    {
        $locale = app()->getLocale();

        $page = Page::query()
            ->where(function ($query) use ($pageKey): void {
                $query->where('key', $pageKey)->orWhere('type', $pageKey);
            })
            ->where('is_published', true)
            ->with('translations')
            ->firstOrFail();

        $translation = $page->translation($locale) ?: $page->translation(config('site.default_locale', 'tr'));
        abort_if(!$translation, 404);

        $canonical = LocaleUrls::abs(config("site.route_translations.{$routeKey}.{$locale}"));

        return view('legal.page', [
            'pageKey' => $pageKey,
            'pageTitle' => $translation->title,
            'pageBody' => $translation->body,
            'legalNotice' => $this->legalNotice($locale),
            'legalLinks' => $this->legalLinks($locale),
            'seo' => $this->seo(
                $translation->seo_title ?: $translation->title,
                $translation->seo_desc ?: strip_tags((string) $translation->body),
                $canonical,
                LocaleUrls::static($routeKey),
            ),
        ]);
    }

    private function legalNotice(string $locale): string
    {
        return match ($locale) {
            'tr' => 'Bu metin bilgilendirme amaçlı bir taslaktır. Nihai kullanım öncesinde hukuki danışman incelemesi önerilir.',
            'en' => 'This text is a draft for informational purposes only. Legal review is recommended before final use.',
            'ru' => 'Этот текст является информационным проектом. Перед окончательным использованием рекомендуется юридическая проверка.',
            'ar' => 'هذا النص مسودة لأغراض معلوماتية فقط، ويُنصح بمراجعته من قبل مستشار قانوني قبل الاستخدام النهائي.',
            'es' => 'Este texto es un borrador con fines informativos. Se recomienda una revisión legal antes del uso final.',
            default => 'This text is a draft for informational purposes only. Legal review is recommended before final use.',
        };
    }

    /**
     * @return array<int, array{route:string,label:string}>
     */
    private function legalLinks(string $locale): array
    {
        return [
            ['route' => "{$locale}.kvkk", 'label' => $this->label('kvkk', $locale)],
            ['route' => "{$locale}.privacy", 'label' => $this->label('privacy', $locale)],
            ['route' => "{$locale}.cookie", 'label' => $this->label('cookie', $locale)],
            ['route' => "{$locale}.distance-sales", 'label' => $this->label('distance-sales', $locale)],
            ['route' => "{$locale}.terms", 'label' => $this->label('terms', $locale)],
        ];
    }

    private function label(string $key, string $locale): string
    {
        $labels = [
            'tr' => [
                'kvkk' => 'KVKK Aydınlatma Metni',
                'privacy' => 'Gizlilik Politikası',
                'cookie' => 'Çerez Politikası',
                'distance-sales' => 'Mesafeli Satış Sözleşmesi',
                'terms' => 'Kullanım Şartları',
            ],
            'en' => [
                'kvkk' => 'KVKK / Privacy Notice',
                'privacy' => 'Privacy Policy',
                'cookie' => 'Cookie Policy',
                'distance-sales' => 'Distance Sales Contract',
                'terms' => 'Terms of Use',
            ],
            'ru' => [
                'kvkk' => 'Уведомление о защите данных',
                'privacy' => 'Политика конфиденциальности',
                'cookie' => 'Политика cookie',
                'distance-sales' => 'Договор дистанционной продажи',
                'terms' => 'Условия использования',
            ],
            'ar' => [
                'kvkk' => 'إشعار حماية البيانات',
                'privacy' => 'سياسة الخصوصية',
                'cookie' => 'سياسة ملفات تعريف الارتباط',
                'distance-sales' => 'عقد البيع عن بُعد',
                'terms' => 'شروط الاستخدام',
            ],
            'es' => [
                'kvkk' => 'Aviso de Privacidad (KVKK)',
                'privacy' => 'Política de Privacidad',
                'cookie' => 'Política de Cookies',
                'distance-sales' => 'Contrato de Venta a Distancia',
                'terms' => 'Términos de Uso',
            ],
        ];

        return $labels[$locale][$key] ?? $labels['en'][$key];
    }
}
