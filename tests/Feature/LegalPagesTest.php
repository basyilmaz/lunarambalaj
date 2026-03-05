<?php

namespace Tests\Feature;

use App\Models\PageTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_legal_pages_are_accessible_in_all_locales(): void
    {
        $paths = [
            '/kvkk',
            '/cerez-politikasi',
            '/gizlilik-politikasi',
            '/en/kvkk',
            '/en/cookie-policy',
            '/en/privacy-policy',
            '/ru/kvkk',
            '/ru/cookie-policy',
            '/ru/privacy-policy',
            '/ar/kvkk',
            '/ar/cookie-policy',
            '/ar/privacy-policy',
        ];

        foreach ($paths as $path) {
            $this->get($path)
                ->assertOk()
                ->assertSee('min-h-[70vh]', false);
        }
    }

    public function test_legal_page_contains_cross_links(): void
    {
        $this->get('/kvkk')
            ->assertOk()
            ->assertSee('/kvkk', false)
            ->assertSee('/cerez-politikasi', false)
            ->assertSee('/gizlilik-politikasi', false);

        $this->get('/en/kvkk')
            ->assertOk()
            ->assertSee('/en/kvkk', false)
            ->assertSee('/en/cookie-policy', false)
            ->assertSee('/en/privacy-policy', false);
    }

    public function test_legal_pages_have_non_empty_long_form_content_for_all_locales(): void
    {
        $translations = PageTranslation::query()
            ->whereIn('page_id', function ($query): void {
                $query->select('id')
                    ->from('pages')
                    ->whereIn('type', ['kvkk', 'cookie', 'privacy']);
            })
            ->get();

        foreach ($translations as $translation) {
            $this->assertGreaterThan(
                400,
                mb_strlen(strip_tags((string) $translation->body)),
                "Legal content too short for lang={$translation->lang}, translation_id={$translation->id}"
            );
        }
    }
}
