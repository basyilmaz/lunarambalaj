<?php

namespace Tests\Feature;

use App\Models\Language;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LocalizedContentCompletenessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_all_enabled_languages_have_core_content(): void
    {
        $languages = Language::query()->pluck('code')->all();

        foreach ($languages as $lang) {
            $this->assertGreaterThan(
                0,
                DB::table('page_translations')->where('lang', $lang)->count(),
                "No page translations for language: {$lang}",
            );

            $this->assertGreaterThan(
                0,
                DB::table('product_translations')->where('lang', $lang)->count(),
                "No product translations for language: {$lang}",
            );

            $this->assertGreaterThan(
                0,
                DB::table('post_translations')->where('lang', $lang)->count(),
                "No post translations for language: {$lang}",
            );

            $this->assertGreaterThan(
                0,
                DB::table('faq_translations')->where('lang', $lang)->count(),
                "No FAQ translations for language: {$lang}",
            );
        }
    }
}

