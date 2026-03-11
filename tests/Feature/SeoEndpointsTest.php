<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_sitemap_returns_xml(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('<?xml', false)
            ->assertSee('/ru', false)
            ->assertSee('/ar', false)
            ->assertSee('/es', false);
    }

    public function test_robots_is_accessible(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /admin', false);
    }

    public function test_llms_is_accessible(): void
    {
        $this->get('/llms.txt')
            ->assertOk()
            ->assertSee('Company Summary', false)
            ->assertSee('Sitemap', false);
    }

    public function test_quote_thank_you_page_has_all_hreflang_links(): void
    {
        $this->get('/ru/get-quote/thank-you')
            ->assertOk()
            ->assertSee('hreflang="tr-TR"', false)
            ->assertSee('hreflang="en"', false)
            ->assertSee('hreflang="ru"', false)
            ->assertSee('hreflang="ar"', false)
            ->assertSee('hreflang="es"', false)
            ->assertSee('hreflang="x-default"', false);
    }
}

