<?php

namespace App\Http\Controllers;

use App\Models\ServiceItem;
use App\Support\LocaleUrls;

class ServiceController extends Controller
{
    public function index()
    {
        $lang = app()->getLocale();

        $services = ServiceItem::query()
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('order')
            ->get();

        return view('services.index', [
            'services' => $services,
            'seo' => $this->seo(
                $lang === 'tr' ? 'Hizmetler | Lunar Ambalaj' : 'Services | Lunar Packaging',
                $lang === 'tr' ? 'Pipet, bardak, peçete, ıslak mendil, bayraklı kürdan ve stick şeker odaklı üretim ve baskı hizmetlerimiz.' : 'Explore our manufacturing and printing services across straws, cups, napkins, wet wipes, flag toothpicks and stick sugar.',
                LocaleUrls::abs(config("site.route_translations.services.{$lang}")),
                LocaleUrls::static('services'),
            ),
        ]);
    }
}
