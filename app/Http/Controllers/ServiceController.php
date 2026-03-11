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

        $seoTitles = [
            'tr' => 'Hizmetler | Lunar Ambalaj',
            'en' => 'Services | Lunar Packaging',
            'ru' => 'Услуги | Lunar Packaging',
            'ar' => 'الخدمات | Lunar Packaging',
            'es' => 'Servicios | Lunar Ambalaj',
        ];
        $seoDescs = [
            'tr' => 'Pipet, bardak, peçete, ıslak mendil, bayraklı kürdan ve stick şeker odaklı üretim ve baskı hizmetleri.',
            'en' => 'Explore manufacturing and printing services across straws, cups, napkins, wet wipes, flag toothpicks and stick sugar.',
            'ru' => 'Услуги производства и печати для трубочек, стаканов, салфеток, влажных салфеток, флажковых зубочисток и stick sugar.',
            'ar' => 'خدمات تصنيع وطباعة للمصاصات والأكواب والمناديل والمناديل المبللة والكردان وسكر الساشيه.',
            'es' => 'Servicios de fabricación e impresión para pajitas, vasos, servilletas, toallitas húmedas, palillos con bandera y azúcar en stick.',
        ];

        return view('services.index', [
            'services' => $services,
            'seo' => $this->seo(
                $seoTitles[$lang] ?? $seoTitles['en'],
                $seoDescs[$lang] ?? $seoDescs['en'],
                LocaleUrls::abs(config("site.route_translations.services.{$lang}")),
                LocaleUrls::static('services'),
            ),
        ]);
    }
}
