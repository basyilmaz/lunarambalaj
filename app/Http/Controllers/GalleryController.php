<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\LocaleUrls;

class GalleryController extends Controller
{
    public function index()
    {
        $lang = app()->getLocale();

        $products = Product::query()
            ->where('is_active', true)
            ->with('translations')
            ->latest()
            ->take(12)
            ->get();

        return view('gallery.index', [
            'products' => $products,
            'seo' => $this->seo(
                $lang === 'tr' ? 'Galeri | Lunar Ambalaj' : 'Gallery | Lunar Packaging',
                $lang === 'tr' ? 'Üretim ve ürün galerisini inceleyin.' : 'Browse our production and product gallery.',
                LocaleUrls::abs(config("site.route_translations.gallery.{$lang}")),
                LocaleUrls::static('gallery'),
            ),
        ]);
    }
}
