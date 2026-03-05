<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Support\LocaleUrls;

class PageController extends Controller
{
    public function about()
    {
        return $this->renderPage('about', 'about');
    }

    public function kvkk()
    {
        return $this->renderPage('kvkk', 'kvkk');
    }

    public function cookie()
    {
        return $this->renderPage('cookie', 'cookie');
    }

    public function privacy()
    {
        return $this->renderPage('privacy', 'privacy');
    }

    private function renderPage(string $type, string $routeKey)
    {
        $lang = app()->getLocale();

        $page = Page::query()
            ->where('type', $type)
            ->where('is_published', true)
            ->with('translations')
            ->firstOrFail();

        $translation = $page->translation($lang);

        abort_if(! $translation, 404);

        $canonical = LocaleUrls::abs(config("site.route_translations.{$routeKey}.{$lang}"));

        return view('page', [
            'pageType' => $type,
            'pageTitle' => $translation->title,
            'pageBody' => $translation->body,
            'seo' => $this->seo(
                $translation->seo_title ?: $translation->title,
                $translation->seo_desc ?: strip_tags($translation->body),
                $canonical,
                LocaleUrls::static($routeKey),
            ),
        ]);
    }
}
