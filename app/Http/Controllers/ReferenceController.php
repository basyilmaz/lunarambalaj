<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use App\Support\LocaleUrls;

class ReferenceController extends Controller
{
    public function index()
    {
        $lang = app()->getLocale();

        $references = Reference::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('references.index', [
            'references' => $references,
            'seo' => $this->seo(
                $lang === 'tr' ? 'Referanslar | Lunar Ambalaj' : 'References | Lunar Packaging',
                $lang === 'tr' ? 'Calistigimiz markalar ve referanslar.' : 'Brands and businesses we work with.',
                LocaleUrls::abs(config("site.route_translations.references.{$lang}")),
                LocaleUrls::static('references'),
            ),
        ]);
    }
}
