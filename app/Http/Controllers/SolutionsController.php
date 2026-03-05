<?php

namespace App\Http\Controllers;

use App\Support\LocaleUrls;

class SolutionsController extends Controller
{
    public function index()
    {
        $lang = app()->getLocale();

        $solutions = [
            [
                'key' => 'cafe',
                'title_tr' => 'Kafe ve Kahve Zincirleri',
                'title_en' => 'Cafe and Coffee Chains',
                'body_tr' => 'Baskılı pipet, karton/PET bardak, peçete ve stick şeker ile tüm servis noktasında marka bütünlüğü sağlayın.',
                'body_en' => 'Build brand consistency across service points with printed plastic straws, cups, napkins and stick sugar.',
                'set_tr' => 'Önerilen set: Baskılı pipet + baskılı bardak + baskılı peçete + stick şeker',
                'set_en' => 'Suggested set: Printed straws + printed cups + printed napkins + stick sugar',
            ],
            [
                'key' => 'fastfood',
                'title_tr' => 'Fast-food',
                'title_en' => 'Fast-food',
                'body_tr' => 'Hızlı servis operasyonlarında standart ölçü, planlı sevkiyat ve tek tedarikçi modeliyle süreci sadeleştirin.',
                'body_en' => 'Simplify high-speed service operations with standard sizes, planned shipments and a single-supplier model.',
                'set_tr' => 'Önerilen set: Baskısız/baskılı pipet + PET bardak + peçete',
                'set_en' => 'Suggested set: Plain/printed straws + PET cups + napkins',
            ],
            [
                'key' => 'hotel',
                'title_tr' => 'Otel ve Catering',
                'title_en' => 'Hotel and Catering',
                'body_tr' => 'Tekli ıslak mendil, bayraklı kürdan ve özel baskılı sarf ürünleriyle premium servis deneyimi oluşturun.',
                'body_en' => 'Create a premium service experience with single sachet wipes, flag toothpicks and custom printed consumables.',
                'set_tr' => 'Önerilen set: Tekli ıslak mendil + bayraklı kürdan + baskılı peçete',
                'set_en' => 'Suggested set: Single sachet wet wipes + flag toothpicks + printed napkins',
            ],
            [
                'key' => 'event',
                'title_tr' => 'Etkinlik ve Organizasyon',
                'title_en' => 'Event and Organization',
                'body_tr' => 'Kampanya dönemlerinde kısa zamanlı sipariş planlaması ve özel tasarımlı ürün setleriyle hızlı uygulama.',
                'body_en' => 'Execute campaigns quickly with short-window order planning and custom designed product sets.',
                'set_tr' => 'Önerilen set: Logo baskılı pipet + karton bardak + stick şeker + bayraklı kürdan',
                'set_en' => 'Suggested set: Logo printed plastic straws + cups + stick sugar + flag toothpicks',
            ],
            [
                'key' => 'retail',
                'title_tr' => 'Perakende ve Dağıtım',
                'title_en' => 'Retail and Distribution',
                'body_tr' => 'Parti bazlı ürün planlaması ve kategori bazlı paketleme seçenekleriyle stok yönetimini kolaylaştırın.',
                'body_en' => 'Improve stock control through batch planning and category-based packaging options.',
                'set_tr' => 'Önerilen set: Kategori bazlı karma koli ve periyodik sevkiyat planlama',
                'set_en' => 'Suggested set: Category mix cartons with periodic shipment planning',
            ],
        ];

        return view('solutions.index', [
            'solutions' => $solutions,
            'seo' => $this->seo(
                $lang === 'tr' ? 'Sektöre Göre Çözümler | Lunar Ambalaj' : 'Solutions by Segment | Lunar Packaging',
                $lang === 'tr' ? 'Kafe, fast-food, otel, catering ve etkinlik operasyonları için ürün setleri ve tedarik çözümleri.' : 'Product sets and supply solutions for cafe, fast-food, hotel, catering and event operations.',
                LocaleUrls::abs(config("site.route_translations.solutions.{$lang}")),
                LocaleUrls::static('solutions'),
            ),
        ]);
    }
}

