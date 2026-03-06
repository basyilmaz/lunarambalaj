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
                'title' => [
                    'tr' => 'Kafe ve Kahve Zincirleri',
                    'en' => 'Cafe and Coffee Chains',
                    'ru' => 'Кафе и кофейные сети',
                    'ar' => 'المقاهي وسلاسل القهوة',
                ],
                'body' => [
                    'tr' => 'Baskılı plastik pipet, bardak, peçete ve stick şeker ile servis noktasında marka bütünlüğünü güçlendirin.',
                    'en' => 'Build brand consistency across service points with printed plastic straws, cups, napkins and stick sugar.',
                    'ru' => 'Усильте целостность бренда в точке сервиса с печатными трубочками, стаканами, салфетками и stick sugar.',
                    'ar' => 'عزّزوا هوية العلامة في نقاط الخدمة عبر المصاصات والأكواب والمناديل والسكر المطبوع.',
                ],
                'set' => [
                    'tr' => 'Baskılı pipet + baskılı bardak + baskılı peçete + stick şeker',
                    'en' => 'Printed straws + printed cups + printed napkins + stick sugar',
                    'ru' => 'Печатные трубочки + печатные стаканы + печатные салфетки + stick sugar',
                    'ar' => 'مصاصات مطبوعة + أكواب مطبوعة + مناديل مطبوعة + سكر ساشيه',
                ],
            ],
            [
                'key' => 'fastfood',
                'title' => [
                    'tr' => 'Fast-food',
                    'en' => 'Fast-food',
                    'ru' => 'Fast-food',
                    'ar' => 'الوجبات السريعة',
                ],
                'body' => [
                    'tr' => 'Hızlı servis operasyonlarında standart ölçüler, planlı sevkiyat ve tek tedarikçi modeliyle süreci sadeleştirin.',
                    'en' => 'Simplify high-speed service operations with standard sizes, planned shipments and a single-supplier model.',
                    'ru' => 'Упростите быстрый сервис за счет стандартных размеров, плановых отгрузок и модели единого поставщика.',
                    'ar' => 'بسّطوا عمليات الخدمة السريعة بالمقاسات القياسية والشحن المخطط ونموذج المورد الواحد.',
                ],
                'set' => [
                    'tr' => 'Baskılı/baskısız pipet + PET bardak + peçete',
                    'en' => 'Printed/plain straws + PET cups + napkins',
                    'ru' => 'Трубочки с печатью/без + PET-стаканы + салфетки',
                    'ar' => 'مصاصات مطبوعة/غير مطبوعة + أكواب PET + مناديل',
                ],
            ],
            [
                'key' => 'hotel',
                'title' => [
                    'tr' => 'Otel ve Catering',
                    'en' => 'Hotel and Catering',
                    'ru' => 'Отель и кейтеринг',
                    'ar' => 'الفنادق والتموين',
                ],
                'body' => [
                    'tr' => 'Tekli ıslak mendil, bayraklı kürdan ve özel baskılı sarf ürünleriyle premium servis deneyimi oluşturun.',
                    'en' => 'Create a premium service experience with single sachet wipes, flag toothpicks and custom printed consumables.',
                    'ru' => 'Создайте премиальный сервис с индивидуальными влажными салфетками, флажковыми зубочистками и печатными расходниками.',
                    'ar' => 'اصنعوا تجربة خدمة مميزة عبر المناديل الفردية والكردان بالأعلام ومنتجات الخدمة المطبوعة.',
                ],
                'set' => [
                    'tr' => 'Tekli ıslak mendil + bayraklı kürdan + baskılı peçete',
                    'en' => 'Single sachet wet wipes + flag toothpicks + printed napkins',
                    'ru' => 'Индивидуальные влажные салфетки + флажковые зубочистки + печатные салфетки',
                    'ar' => 'مناديل مبللة فردية + كردان بالأعلام + مناديل مطبوعة',
                ],
            ],
            [
                'key' => 'event',
                'title' => [
                    'tr' => 'Etkinlik ve Organizasyon',
                    'en' => 'Event and Organization',
                    'ru' => 'Мероприятия и организация',
                    'ar' => 'الفعاليات والتنظيم',
                ],
                'body' => [
                    'tr' => 'Kampanya dönemlerinde kısa zamanlı sipariş planlaması ve özel tasarımlı ürün setleriyle hızlı uygulama yapın.',
                    'en' => 'Execute campaigns quickly with short-window order planning and custom designed product sets.',
                    'ru' => 'Запускайте кампании быстрее благодаря короткому циклу заказа и индивидуальным наборам.',
                    'ar' => 'نفّذوا الحملات بسرعة عبر تخطيط الطلبات القصير ومجموعات المنتجات المصممة خصيصًا.',
                ],
                'set' => [
                    'tr' => 'Logo baskılı pipet + karton bardak + stick şeker + bayraklı kürdan',
                    'en' => 'Logo printed plastic straws + cups + stick sugar + flag toothpicks',
                    'ru' => 'Трубочки с логотипом + стаканы + stick sugar + флажковые зубочистки',
                    'ar' => 'مصاصات بشعار + أكواب + سكر ساشيه + كردان بالأعلام',
                ],
            ],
            [
                'key' => 'retail',
                'title' => [
                    'tr' => 'Perakende ve Dağıtım',
                    'en' => 'Retail and Distribution',
                    'ru' => 'Розница и дистрибуция',
                    'ar' => 'التجزئة والتوزيع',
                ],
                'body' => [
                    'tr' => 'Parti bazlı üretim planı ve kategori bazlı paketleme seçenekleriyle stok yönetimini kolaylaştırın.',
                    'en' => 'Improve stock control through batch planning and category-based packaging options.',
                    'ru' => 'Оптимизируйте запасы за счет партийного планирования и категорийной упаковки.',
                    'ar' => 'حسّنوا إدارة المخزون عبر التخطيط على دفعات وخيارات التغليف حسب الفئة.',
                ],
                'set' => [
                    'tr' => 'Kategori bazlı karma koli + periyodik sevkiyat planlama',
                    'en' => 'Category mix cartons + periodic shipment planning',
                    'ru' => 'Смешанные короба по категориям + план периодических отгрузок',
                    'ar' => 'كراتين مختلطة حسب الفئة + خطة شحن دورية',
                ],
            ],
        ];

        $seoTitles = [
            'tr' => 'Sektöre Göre Çözümler | Lunar Ambalaj',
            'en' => 'Solutions by Segment | Lunar Packaging',
            'ru' => 'Решения по сегментам | Lunar Packaging',
            'ar' => 'حلول حسب القطاع | Lunar Packaging',
        ];
        $seoDescs = [
            'tr' => 'Kafe, fast-food, otel, catering ve etkinlik operasyonları için ürün setleri ve tek tedarikçi modeli.',
            'en' => 'Product set planning and single-supplier model for cafes, fast-food, hotels, catering and events.',
            'ru' => 'Наборы продуктов и модель единого поставщика для кафе, fast-food, отелей, кейтеринга и мероприятий.',
            'ar' => 'تخطيط مجموعات المنتجات ونموذج المورد الواحد للمقاهي والوجبات السريعة والفنادق والتموين والفعاليات.',
        ];

        return view('solutions.index', [
            'solutions' => $solutions,
            'seo' => $this->seo(
                $seoTitles[$lang] ?? $seoTitles['en'],
                $seoDescs[$lang] ?? $seoDescs['en'],
                LocaleUrls::abs(config("site.route_translations.solutions.{$lang}")),
                LocaleUrls::static('solutions'),
            ),
        ]);
    }
}

