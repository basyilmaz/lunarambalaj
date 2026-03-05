<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        config(['app.url' => 'https://lunarambalaj.com']);
    }

    public function test_legal_pages_have_notice_in_all_languages(): void
    {
        $cases = [
            ['/kvkk', 'Bu metin bilgilendirme amaçlı bir taslaktır.'],
            ['/gizlilik-politikasi', 'Bu metin bilgilendirme amaçlı bir taslaktır.'],
            ['/cerez-politikasi', 'Bu metin bilgilendirme amaçlı bir taslaktır.'],
            ['/mesafeli-satis-sozlesmesi', 'Bu metin bilgilendirme amaçlı bir taslaktır.'],
            ['/kullanim-sartlari', 'Bu metin bilgilendirme amaçlı bir taslaktır.'],
            ['/en/kvkk', 'This text is a draft for informational purposes only.'],
            ['/en/privacy-policy', 'This text is a draft for informational purposes only.'],
            ['/en/cookie-policy', 'This text is a draft for informational purposes only.'],
            ['/en/distance-sales-contract', 'This text is a draft for informational purposes only.'],
            ['/en/terms-of-use', 'This text is a draft for informational purposes only.'],
            ['/ru/kvkk', 'Этот текст является информационным проектом.'],
            ['/ru/privacy-policy', 'Этот текст является информационным проектом.'],
            ['/ru/cookie-policy', 'Этот текст является информационным проектом.'],
            ['/ru/distance-sales-contract', 'Этот текст является информационным проектом.'],
            ['/ru/terms-of-use', 'Этот текст является информационным проектом.'],
            ['/ar/kvkk', 'هذا النص مسودة لأغراض معلوماتية فقط'],
            ['/ar/privacy-policy', 'هذا النص مسودة لأغراض معلوماتية فقط'],
            ['/ar/cookie-policy', 'هذا النص مسودة لأغراض معلوماتية فقط'],
            ['/ar/distance-sales-contract', 'هذا النص مسودة لأغراض معلوماتية فقط'],
            ['/ar/terms-of-use', 'هذا النص مسودة لأغراض معلوماتية فقط'],
        ];

        foreach ($cases as [$url, $notice]) {
            $this->get($url)->assertOk()->assertSee($notice, false);
        }
    }

    public function test_arabic_legal_page_uses_rtl_and_has_full_hreflang_set(): void
    {
        $this->get('/ar/kvkk')
            ->assertOk()
            ->assertSee('lang="ar"', false)
            ->assertSee('dir="rtl"', false)
            ->assertSee('hreflang="tr-TR"', false)
            ->assertSee('hreflang="en"', false)
            ->assertSee('hreflang="ru"', false)
            ->assertSee('hreflang="ar"', false)
            ->assertSee('hreflang="x-default"', false);
    }

    public function test_legal_page_canonical_uses_primary_domain(): void
    {
        $this->get('/en/privacy-policy')
            ->assertOk()
            ->assertSee('rel="canonical" href="https://lunarambalaj.com/en/privacy-policy"', false);
    }
}

