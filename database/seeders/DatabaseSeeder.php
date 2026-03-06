<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\AdIntegration;
use App\Models\ConversionMapping;
use App\Models\Language;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Reference;
use App\Models\TrackingEvent;
use App\Models\ServiceItem;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->resetSeedTablesForLocal();

        User::query()->updateOrCreate(
            ['email' => 'admin@lunarambalaj.com.tr'],
            ['name' => 'Admin', 'password' => Hash::make('password'), 'role' => 'admin'],
        );

        Language::query()->updateOrCreate(['code' => 'tr'], ['name' => 'Türkçe', 'is_default' => true]);
        Language::query()->updateOrCreate(['code' => 'en'], ['name' => 'English', 'is_default' => false]);
        Language::query()->updateOrCreate(['code' => 'ru'], ['name' => 'Русский', 'is_default' => false]);
        Language::query()->updateOrCreate(['code' => 'ar'], ['name' => 'العربية', 'is_default' => false]);

        $pages = [
            'about' => [
                'tr' => [
                    'title' => 'Hakkımızda',
                    'slug' => 'hakkimizda',
                    'body' => 'Lunar Ambalaj; plastik frozen pipet, çok amaçlı pipet ve özel ölçü pipet üretiminde uzmanlaşmış üretici bir markadır. Horeca ve kurumsal satın alma ekipleri için pipet, bardak, peçete, ıslak mendil, bayraklı kürdan ve stick şeker gruplarında tek tedarik yaklaşımı sunar. Kağıt pipet üretimi ise talebe bağlı fason modelde yürütülür.',
                ],
                'en' => [
                    'title' => 'About',
                    'slug' => 'about',
                    'body' => 'Lunar Packaging is a manufacturer focused on plastic frozen straws, multifunction straws and custom-size straws. We provide a single-supplier model for horeca and corporate purchasing teams across straws, cups, napkins, wet wipes, flag toothpicks and stick sugar. Paper straws are supplied in contract manufacturing mode when requested.',
                ],
                'ru' => [
                    'title' => 'О нас',
                    'slug' => 'about',
                    'body' => 'Lunar Packaging — производитель пластиковых трубочек для замороженных напитков, многофункциональных и трубочек по индивидуальным размерам. Мы предлагаем модель единого поставщика для команд закупок HoReCa и корпоративного сектора: трубочки, стаканы, салфетки, влажные салфетки, зубочистки с флажками и сахар в стиках. Бумажные трубочки поставляются по контрактному производству по запросу.',
                ],
                'ar' => [
                    'title' => 'معلومات عنا',
                    'slug' => 'about',
                    'body' => 'Lunar Packaging هي شركة تصنيع متخصصة في المصاصات البلاستيكية المجمدة، والمصاصات متعددة الاستخدامات والمصاصات ذات الأحجام المخصصة. نوفر نموذج مورد واحد لفرق المشتريات في قطاع HoReCa والقطاع المؤسسي: المصاصات والأكواب والمناديل والمناديل المبللة وأعواد الأسنان بالأعلام والسكر في العصي. يتم توفير المصاصات الورقية عبر التصنيع التعاقدي عند الطلب.',
                ],
            ],
            'kvkk' => [
                'tr' => [
                    'title' => 'KVKK',
                    'slug' => 'kvkk',
                    'body' => 'Kişisel verileriniz 6698 sayılı Kanun kapsamında yalnızca teklif, sipariş ve iletişim süreçlerinin yürütülmesi amacıyla işlenir. Bilgi taleplerinizi info@lunarambalaj.com.tr adresine iletebilirsiniz.',
                ],
                'en' => [
                    'title' => 'KVKK',
                    'slug' => 'kvkk',
                    'body' => 'Personal data is processed only for quotation, order and communication workflows in line with Turkish Personal Data Protection Law No. 6698. You can send requests to info@lunarambalaj.com.tr.',
                ],
                'ru' => [
                    'title' => 'KVKK',
                    'slug' => 'kvkk',
                    'body' => 'Персональные данные обрабатываются только для процессов котировок, заказов и коммуникации в соответствии с Турецким законом о защите персональных данных № 6698. Вы можете отправлять запросы на info@lunarambalaj.com.tr.',
                ],
                'ar' => [
                    'title' => 'KVKK',
                    'slug' => 'kvkk',
                    'body' => 'تتم معالجة البيانات الشخصية فقط لسير عمل عروض الأسعار والطلبات والاتصالات وفقًا لقانون حماية البيانات الشخصية التركي رقم 6698. يمكنك إرسال الطلبات إلى info@lunarambalaj.com.tr.',
                ],
            ],
            'cookie' => [
                'tr' => [
                    'title' => 'Çerez Politikası',
                    'slug' => 'cerez-politikasi',
                    'body' => 'Sitede temel işlev, performans ve ölçümleme amaçlı çerezler kullanılır. Tercihlerinizi tarayıcı ayarlarından yönetebilirsiniz.',
                ],
                'en' => [
                    'title' => 'Cookie Policy',
                    'slug' => 'cookie-policy',
                    'body' => 'The site uses essential, performance and measurement cookies. You can manage preferences through your browser settings.',
                ],
                'ru' => [
                    'title' => 'Политика использования файлов cookie',
                    'slug' => 'cookie-policy',
                    'body' => 'На сайте используются основные, производительные и измерительные файлы cookie. Вы можете управлять предпочтениями через настройки браузера.',
                ],
                'ar' => [
                    'title' => 'سياسة ملفات تعريف الارتباط',
                    'slug' => 'cookie-policy',
                    'body' => 'يستخدم الموقع ملفات تعريف الارتباط الأساسية والأداء والقياس. يمكنك إدارة التفضيلات من خلال إعدادات المتصفح.',
                ],
            ],
            'privacy' => [
                'tr' => [
                    'title' => 'Gizlilik Politikası',
                    'slug' => 'gizlilik-politikasi',
                    'body' => 'Lunar Ambalaj, iletilen verileri gizlilik ve bilgi güvenliği ilkeleri doğrultusunda saklar. Formlardan toplanan bilgiler yalnızca iletişim ve teklif süreçleri için kullanılır.',
                ],
                'en' => [
                    'title' => 'Privacy Policy',
                    'slug' => 'privacy-policy',
                    'body' => 'Lunar Packaging stores submitted data according to privacy and information security principles. Form data is used only for contact and quotation workflows.',
                ],
                'ru' => [
                    'title' => 'Политика конфиденциальности',
                    'slug' => 'privacy-policy',
                    'body' => 'Lunar Packaging хранит предоставленные данные в соответствии с принципами конфиденциальности и информационной безопасности. Данные формы используются только для процессов связи и котировок.',
                ],
                'ar' => [
                    'title' => 'سياسة الخصوصية',
                    'slug' => 'privacy-policy',
                    'body' => 'تخزن Lunar Packaging البيانات المقدمة وفقًا لمبادئ الخصوصية وأمن المعلومات. تُستخدم بيانات النموذج فقط لسير عمل الاتصال وعروض الأسعار.',
                ],
            ],
        ];

        foreach ($pages as $type => $translations) {
            $page = Page::query()->updateOrCreate(['type' => $type], ['is_published' => true]);

            foreach ($translations as $lang => $t) {
                $seoBrandByLang = [
                    'tr' => 'Lunar Ambalaj',
                    'en' => 'Lunar Packaging',
                    'ru' => 'Lunar Packaging',
                    'ar' => 'Lunar Packaging',
                ];

                $page->translations()->updateOrCreate(
                    ['lang' => $lang],
                    [
                        'title' => $t['title'],
                        'slug' => $t['slug'],
                        'body' => $t['body'],
                        'seo_title' => mb_substr($t['seo_title'] ?? ($t['title'] . ' | ' . ($seoBrandByLang[$lang] ?? 'Lunar Packaging')), 0, 60),
                        'seo_desc' => mb_substr($t['seo_desc'] ?? strip_tags($t['body']), 0, 160),
                    ],
                );
            }
        }

        $services = [
            [
                'icon' => 'images/catalog/asset-01.png',
                'tr' => ['title' => 'Plastik Pipet Üretimi', 'body' => 'Frozen ve çok amaçlı plastik pipetlerde farklı çap, boy ve renk seçenekleriyle seri üretim.'],
                'en' => ['title' => 'Plastic Straw Manufacturing', 'body' => 'Series production for frozen and multifunction plastic straws in multiple diameters, lengths and colors.'],
                'ru' => ['title' => 'Производство пластиковых трубочек', 'body' => 'Серийное производство трубочек для замороженных напитков и многофункциональных трубочек с различными диаметрами, длинами и цветами.'],
                'ar' => ['title' => 'تصنيع المصاصات البلاستيكية', 'body' => 'الإنتاج المسلسل للمصاصات المجمدة والمصاصات متعددة الوظائف بأقطار وأطوال وألوان متعددة.'],
            ],
            [
                'icon' => 'images/catalog/asset-02.png',
                'tr' => ['title' => 'Özel Baskı', 'body' => 'Pipet, bardak, peçete ve stick ambalajlarda kurumsal logolu baskı çözümleri.'],
                'en' => ['title' => 'Custom Printing', 'body' => 'Corporate logo printing solutions for straws, cups, napkins and stick packaging.'],
                'ru' => ['title' => 'Индивидуальная печать', 'body' => 'Решения по печати корпоративных логотипов для трубочек, стаканов, салфеток и упаковки в стиках.'],
                'ar' => ['title' => 'طباعة مخصصة', 'body' => 'حلول طباعة الشعار المؤسسي للمصاصات والأكواب والمناديل وتعبئة العصي.'],
            ],
            [
                'icon' => 'images/catalog/asset-03.png',
                'tr' => ['title' => 'Bardak Üretimi', 'body' => 'Karton ve PET bardak gruplarında özel ölçü ve baskı alternatifleri.'],
                'en' => ['title' => 'Cup Production', 'body' => 'Paper and PET cup groups with custom size and print alternatives.'],
                'ru' => ['title' => 'Производство стаканов', 'body' => 'Группы бумажных и PET стаканов с индивидуальными размерами и альтернативами печати.'],
                'ar' => ['title' => 'إنتاج الأكواب', 'body' => 'مجموعات أكواب ورقية وبولي إيثيلين تيريفثالات بأحجام مخصصة وبدائل طباعة.'],
            ],
            [
                'icon' => 'images/catalog/asset-04.png',
                'tr' => ['title' => 'Jelatinleme ve Tekli Paket', 'body' => 'Hijyen odaklı servis modelleri için tekli paketleme ve koli planlama.'],
                'en' => ['title' => 'Wrapping and Single Pack', 'body' => 'Single-pack wrapping and carton planning for hygiene-focused service models.'],
                'ru' => ['title' => 'Упаковка и индивидуальная упаковка', 'body' => 'Индивидуальная упаковка и планирование картонных коробок для моделей обслуживания, ориентированных на гигиену.'],
                'ar' => ['title' => 'التغليف والعبوة الفردية', 'body' => 'التغليف الفردي وتخطيط الكرتون لنماذج الخدمة الموجهة للنظافة.'],
            ],
            [
                'icon' => 'images/catalog/asset-05.png',
                'tr' => ['title' => 'Islak Mendil Private Label', 'body' => 'Restoran ve otel segmentine özel private label ıslak mendil üretimi.'],
                'en' => ['title' => 'Private Label Wet Wipes', 'body' => 'Private label wet wipe production for restaurant and hospitality segments.'],
                'ru' => ['title' => 'Влажные салфетки под частной маркой', 'body' => 'Производство влажных салфеток под частной маркой для ресторанов и гостиничного сектора.'],
                'ar' => ['title' => 'المناديل المبللة بالعلامة الخاصة', 'body' => 'إنتاج المناديل المبللة بالعلامة الخاصة لقطاعات المطاعم والضيافة.'],
            ],
            [
                'icon' => 'images/catalog/asset-06.png',
                'tr' => ['title' => 'Stick Şeker ve Sunum Ürünleri', 'body' => 'Stick şeker ve bayraklı kürdan ürünlerinde kurumsal sunum setleri.'],
                'en' => ['title' => 'Stick Sugar and Serving Items', 'body' => 'Corporate serving sets with stick sugar and flag toothpick products.'],
                'ru' => ['title' => 'Сахар в стиках и сервировочные изделия', 'body' => 'Корпоративные сервировочные наборы с сахаром в стиках и зубочистками с флажками.'],
                'ar' => ['title' => 'السكر في العصي وأدوات التقديم', 'body' => 'مجموعات تقديم مؤسسية مع السكر في العصي ومنتجات أعواد الأسنان بالأعلام.'],
            ],
        ];

        foreach ($services as $index => $serviceData) {
            $service = ServiceItem::query()->updateOrCreate(
                ['order' => $index + 1],
                ['icon' => $serviceData['icon'], 'is_active' => true],
            );

            $service->translations()->updateOrCreate(['lang' => 'tr'], $serviceData['tr']);
            $service->translations()->updateOrCreate(['lang' => 'en'], $serviceData['en']);
            $service->translations()->updateOrCreate(['lang' => 'ru'], $serviceData['ru']);
            $service->translations()->updateOrCreate(['lang' => 'ar'], $serviceData['ar']);
        }

        $categories = [
            ['order' => 1, 'tr' => ['name' => 'Pipet', 'slug' => 'pipet'], 'en' => ['name' => 'Straws', 'slug' => 'straws'], 'ru' => ['name' => 'Трубочки', 'slug' => 'straws'], 'ar' => ['name' => 'المصاصات', 'slug' => 'straws']],
            ['order' => 2, 'tr' => ['name' => 'Bardak', 'slug' => 'bardak'], 'en' => ['name' => 'Cups', 'slug' => 'cups'], 'ru' => ['name' => 'Стаканы', 'slug' => 'cups'], 'ar' => ['name' => 'الأكواب', 'slug' => 'cups']],
            ['order' => 3, 'tr' => ['name' => 'Peçete', 'slug' => 'pecete'], 'en' => ['name' => 'Napkins', 'slug' => 'napkins'], 'ru' => ['name' => 'Салфетки', 'slug' => 'napkins'], 'ar' => ['name' => 'المناديل', 'slug' => 'napkins']],
            ['order' => 4, 'tr' => ['name' => 'Islak Mendil', 'slug' => 'islak-mendil'], 'en' => ['name' => 'Wet Wipes', 'slug' => 'wet-wipes'], 'ru' => ['name' => 'Влажные салфетки', 'slug' => 'wet-wipes'], 'ar' => ['name' => 'المناديل المبللة', 'slug' => 'wet-wipes']],
            ['order' => 5, 'tr' => ['name' => 'Bayraklı Kürdan', 'slug' => 'bayrakli-kurdan'], 'en' => ['name' => 'Flag Toothpicks', 'slug' => 'flag-toothpicks'], 'ru' => ['name' => 'Зубочистки с флажками', 'slug' => 'flag-toothpicks'], 'ar' => ['name' => 'أعواد الأسنان بالأعلام', 'slug' => 'flag-toothpicks']],
            ['order' => 6, 'tr' => ['name' => 'Stick Şeker', 'slug' => 'stick-seker'], 'en' => ['name' => 'Stick Sugar', 'slug' => 'stick-sugar'], 'ru' => ['name' => 'Сахар в стиках', 'slug' => 'stick-sugar'], 'ar' => ['name' => 'السكر في العصي', 'slug' => 'stick-sugar']],
        ];

        $categoryMap = [];
        foreach ($categories as $categoryData) {
            $category = ProductCategory::query()->updateOrCreate(
                ['order' => $categoryData['order']],
                ['is_active' => true],
            );

            $category->translations()->updateOrCreate(['lang' => 'tr'], $categoryData['tr']);
            $category->translations()->updateOrCreate(['lang' => 'en'], $categoryData['en']);
            $category->translations()->updateOrCreate(['lang' => 'ru'], $categoryData['ru']);
            $category->translations()->updateOrCreate(['lang' => 'ar'], $categoryData['ar']);
            $categoryMap[$categoryData['tr']['slug']] = $category;
        }

        $productSeeds = [
            ['category' => 'pipet', 'tr' => 'Plastik Frozen Pipet', 'en' => 'Plastic Frozen Straws', 'ru' => 'Пластиковые трубочки для frozen', 'ar' => 'مصاصات بلاستيكية للمشروبات المجمّدة', 'slug_tr' => 'plastik-frozen-pipet', 'slug_en' => 'plastic-frozen-straws', 'print' => true, 'wrap' => false, 'image' => 'images/catalog/asset-07.png'],
            ['category' => 'pipet', 'tr' => 'Çok Amaçlı Plastik Pipet', 'en' => 'Multifunction Plastic Straws', 'ru' => 'Многофункциональные пластиковые трубочки', 'ar' => 'مصاصات بلاستيكية متعددة الاستخدامات', 'slug_tr' => 'cok-amacli-plastik-pipet', 'slug_en' => 'multifunction-plastic-straws', 'print' => true, 'wrap' => false, 'image' => 'images/catalog/asset-08.png'],
            ['category' => 'pipet', 'tr' => 'Özel Ölçü Plastik Pipet', 'en' => 'Custom Size Plastic Straws', 'ru' => 'Пластиковые трубочки по индивидуальному размеру', 'ar' => 'مصاصات بلاستيكية بمقاسات خاصة', 'slug_tr' => 'ozel-olcu-plastik-pipet', 'slug_en' => 'custom-size-plastic-straws', 'print' => true, 'wrap' => true, 'image' => 'images/catalog/asset-09.jpg'],
            ['category' => 'pipet', 'tr' => 'Kağıt Pipet (Fason Üretim)', 'en' => 'Paper Straws (Contract Manufacturing)', 'ru' => 'Бумажные трубочки (контрактное производство)', 'ar' => 'مصاصات ورقية (تصنيع تعاقدي)', 'slug_tr' => 'kagit-pipet-fason-uretim', 'slug_en' => 'paper-straws-contract-manufacturing', 'print' => true, 'wrap' => false, 'image' => 'images/catalog/asset-10.jpg'],

            ['category' => 'bardak', 'tr' => 'Baskılı Karton Bardak', 'en' => 'Printed Paper Cups', 'ru' => 'Бумажные стаканы с печатью', 'ar' => 'أكواب ورقية مطبوعة', 'slug_tr' => 'baskili-karton-bardak', 'slug_en' => 'printed-paper-cups', 'print' => true, 'wrap' => false, 'image' => 'images/catalog/asset-11.jpg'],
            ['category' => 'bardak', 'tr' => 'Baskısız Karton Bardak', 'en' => 'Plain Paper Cups', 'ru' => 'Бумажные стаканы без печати', 'ar' => 'أكواب ورقية بدون طباعة', 'slug_tr' => 'baskisiz-karton-bardak', 'slug_en' => 'plain-paper-cups', 'print' => false, 'wrap' => false, 'image' => 'images/catalog/asset-12.jpg'],
            ['category' => 'bardak', 'tr' => 'PET Bardak', 'en' => 'PET Cups', 'ru' => 'PET-стаканы', 'ar' => 'أكواب PET', 'slug_tr' => 'pet-bardak', 'slug_en' => 'pet-cups', 'print' => false, 'wrap' => false, 'image' => 'images/catalog/asset-13.jpg'],
            ['category' => 'bardak', 'tr' => 'Baskılı PET Bardak', 'en' => 'Printed PET Cups', 'ru' => 'PET-стаканы с печатью', 'ar' => 'أكواب PET مطبوعة', 'slug_tr' => 'baskili-pet-bardak', 'slug_en' => 'printed-pet-cups', 'print' => true, 'wrap' => false, 'image' => 'images/catalog/asset-14.jpg'],

            ['category' => 'pecete', 'tr' => 'Baskılı Peçete', 'en' => 'Printed Napkins', 'ru' => 'Салфетки с печатью', 'ar' => 'مناديل مطبوعة', 'slug_tr' => 'baskili-pecete', 'slug_en' => 'printed-napkins', 'print' => true, 'wrap' => false, 'image' => 'images/catalog/asset-15.jpg'],
            ['category' => 'pecete', 'tr' => 'Baskısız Peçete', 'en' => 'Plain Napkins', 'ru' => 'Салфетки без печати', 'ar' => 'مناديل بدون طباعة', 'slug_tr' => 'baskisiz-pecete', 'slug_en' => 'plain-napkins', 'print' => false, 'wrap' => false, 'image' => 'images/catalog/asset-16.jpg'],

            ['category' => 'islak-mendil', 'tr' => 'Tekli Islak Mendil', 'en' => 'Single Sachet Wet Wipes', 'ru' => 'Влажные салфетки в саше', 'ar' => 'مناديل مبللة فردية (ساشيه)', 'slug_tr' => 'tekli-islak-mendil', 'slug_en' => 'single-sachet-wet-wipes', 'print' => true, 'wrap' => true, 'image' => 'images/catalog/asset-17.jpg'],
            ['category' => 'islak-mendil', 'tr' => 'Özel Marka Islak Mendil', 'en' => 'Private Label Wet Wipes', 'ru' => 'Влажные салфетки private label', 'ar' => 'مناديل مبللة بعلامة خاصة', 'slug_tr' => 'ozel-marka-islak-mendil', 'slug_en' => 'private-label-wet-wipes', 'print' => true, 'wrap' => true, 'image' => 'images/catalog/asset-18.jpg'],
            ['category' => 'islak-mendil', 'tr' => 'Restoran Tipi Islak Mendil', 'en' => 'Restaurant Wet Wipes', 'ru' => 'Влажные салфетки для ресторанов', 'ar' => 'مناديل مبللة لقطاع المطاعم', 'slug_tr' => 'restoran-tipi-islak-mendil', 'slug_en' => 'restaurant-wet-wipes', 'print' => true, 'wrap' => true, 'image' => 'images/catalog/asset-19.jpg'],

            ['category' => 'bayrakli-kurdan', 'tr' => 'Bayraklı Kürdan Standart', 'en' => 'Flag Toothpicks Standard', 'ru' => 'Зубочистки с флажком (стандарт)', 'ar' => 'أعواد أسنان بعلم (قياسي)', 'slug_tr' => 'bayrakli-kurdan-standart', 'slug_en' => 'flag-toothpicks-standard', 'print' => true, 'wrap' => false, 'image' => 'images/catalog/asset-20.jpg'],
            ['category' => 'bayrakli-kurdan', 'tr' => 'Özel Ölçü Bayraklı Kürdan', 'en' => 'Custom Size Flag Toothpicks', 'ru' => 'Зубочистки с флажком (индивидуальный размер)', 'ar' => 'أعواد أسنان بعلم بمقاس خاص', 'slug_tr' => 'ozel-olcu-bayrakli-kurdan', 'slug_en' => 'custom-size-flag-toothpicks', 'print' => true, 'wrap' => false, 'image' => 'images/catalog/asset-21.jpg'],

            ['category' => 'stick-seker', 'tr' => 'Baskılı Stick Şeker', 'en' => 'Printed Stick Sugar', 'ru' => 'Сахар в стиках с печатью', 'ar' => 'سكر أكياس عصوية مطبوع', 'slug_tr' => 'baskili-stick-seker', 'slug_en' => 'printed-stick-sugar', 'print' => true, 'wrap' => true, 'image' => 'images/catalog/asset-22.jpg'],
            ['category' => 'stick-seker', 'tr' => 'Baskısız Stick Şeker', 'en' => 'Plain Stick Sugar', 'ru' => 'Сахар в стиках без печати', 'ar' => 'سكر أكياس عصوية بدون طباعة', 'slug_tr' => 'baskisiz-stick-seker', 'slug_en' => 'plain-stick-sugar', 'print' => false, 'wrap' => true, 'image' => 'images/catalog/asset-23.jpg'],
            ['category' => 'stick-seker', 'tr' => 'Logolu Stick Şeker', 'en' => 'Logo Printed Stick Sugar', 'ru' => 'Сахар в стиках с логотипом', 'ar' => 'سكر أكياس عصوية بطباعة الشعار', 'slug_tr' => 'logolu-stick-seker', 'slug_en' => 'logo-printed-stick-sugar', 'print' => true, 'wrap' => true, 'image' => 'images/catalog/asset-24.jpg'],
        ];

        foreach ($productSeeds as $productSeed) {
            $category = $categoryMap[$productSeed['category']];

            $product = Product::query()->create([
                'category_id' => $category->id,
                'min_order' => 5000,
                'has_print' => $productSeed['print'],
                'has_wrapping' => $productSeed['wrap'],
                'is_active' => true,
                'image' => $productSeed['image'],
            ]);

            $trDescription = implode("\n", [
                $productSeed['tr'] . ' ürününde marka kimliğinize uygun teknik ve baskı seçenekleri sunulur.',
                'Teknik Özellikler:',
                '- Ölçü seçenekleri: kategoriye göre mm/oz bazlı planlanır.',
                '- Baskı: CMYK / logolu uygulama seçenekleri.',
                '- Paketleme: koli içi adet ve tekli sarım opsiyonu ürüne göre değişir.',
                '- Min. Sipariş: 5000 adet (ürün bazlı güncellenebilir).',
                '- Termin: teklif sürecinde sipariş hacmine göre planlanır.',
            ]);

            $enDescription = implode("\n", [
                $productSeed['en'] . ' is delivered with technical and branding options tailored to your operation.',
                'Technical Specifications:',
                '- Size options: planned in mm/oz according to category.',
                '- Printing: CMYK and logo-based customization.',
                '- Packaging: carton quantity and single-wrap options vary by product.',
                '- Minimum Order: 5000 units (can be overridden per product).',
                '- Lead Time: confirmed during quotation according to order volume.',
            ]);

            $ruDescription = implode("\n", [
                $productSeed['ru'] . ' поставляется с техническими параметрами и возможностью брендирования под ваш проект.',
                'Технические характеристики:',
                '- Размеры: варианты в мм/oz под категорию и сценарий использования.',
                '- Печать: CMYK и фирменный логотип.',
                '- Упаковка: количество в коробке и индивидуальная упаковка по продукту.',
                '- Минимальный заказ: 5000 шт. (можно уточнить по продукту).',
                '- Срок: подтверждается на этапе коммерческого предложения.',
            ]);

            $arDescription = implode("\n", [
                $productSeed['ar'] . ' يُقدَّم بخيارات فنية وخيارات طباعة مخصّصة لهوية علامتكم.',
                'المواصفات الفنية:',
                '- المقاسات: خيارات بالملمتر/الأونصة حسب الفئة وسيناريو الاستخدام.',
                '- الطباعة: CMYK مع إمكانية طباعة الشعار.',
                '- التغليف: عدد القطع في الكرتون وخيار التغليف الفردي حسب المنتج.',
                '- الحد الأدنى للطلب: 5000 قطعة (قابل للتخصيص حسب المنتج).',
                '- مدة التسليم: تُحدَّد أثناء عرض السعر وفق حجم الطلب.',
            ]);

            $product->translations()->updateOrCreate(
                ['lang' => 'tr'],
                [
                    'name' => $productSeed['tr'],
                    'slug' => $productSeed['slug_tr'],
                    'short_desc' => $productSeed['tr'] . ' ile kurumsal sunum ve servis standardınızı güçlendirin.',
                    'description' => $trDescription,
                    'seo_title' => mb_substr($productSeed['tr'] . ' | Lunar Ambalaj', 0, 60),
                    'seo_desc' => mb_substr($productSeed['tr'] . ' ürününde teknik seçenekler, min sipariş ve hızlı teklif.', 0, 160),
                ],
            );

            $product->translations()->updateOrCreate(
                ['lang' => 'en'],
                [
                    'name' => $productSeed['en'],
                    'slug' => $productSeed['slug_en'],
                    'short_desc' => 'Strengthen your branded service setup with ' . Str::lower($productSeed['en']) . '.',
                    'description' => $enDescription,
                    'seo_title' => mb_substr($productSeed['en'] . ' | Lunar Packaging', 0, 60),
                    'seo_desc' => mb_substr('Technical options, minimum order and fast quote workflow for ' . Str::lower($productSeed['en']) . '.', 0, 160),
                ],
            );

            $product->translations()->updateOrCreate(
                ['lang' => 'ru'],
                [
                    'name' => $productSeed['ru'],
                    'slug' => $productSeed['slug_en'],
                    'short_desc' => 'Усильте фирменную подачу с продуктом: ' . $productSeed['ru'] . '.',
                    'description' => $ruDescription,
                    'seo_title' => mb_substr($productSeed['ru'] . ' | Lunar Packaging', 0, 60),
                    'seo_desc' => mb_substr('Технические параметры, MOQ и быстрый расчет для продукта: ' . $productSeed['ru'] . '.', 0, 160),
                ],
            );

            $product->translations()->updateOrCreate(
                ['lang' => 'ar'],
                [
                    'name' => $productSeed['ar'],
                    'slug' => $productSeed['slug_en'],
                    'short_desc' => 'عزّز هوية علامتك مع منتج: ' . $productSeed['ar'] . '.',
                    'description' => $arDescription,
                    'seo_title' => mb_substr($productSeed['ar'] . ' | Lunar Packaging', 0, 60),
                    'seo_desc' => mb_substr('مواصفات فنية وحد أدنى للطلب وعرض سعر سريع لمنتج: ' . $productSeed['ar'] . '.', 0, 160),
                ],
            );
        }

        $posts = [
            [
                'tr' => 'Plastik frozen pipet seçerken kritik kriterler',
                'en' => 'Critical criteria when selecting plastic frozen straws',
                'ru' => 'Критические критерии при выборе пластиковых трубочек для замороженных напитков',
                'ar' => 'المعايير الحاسمة عند اختيار المصاصات البلاستيكية المجمدة',
                'tr_body' => "Plastik frozen pipet seçiminde dikkat edilmesi gereken en önemli kriterler çap ölçüsü, dayanıklılık ve baskı kalitesidir.\n\nÇap Seçimi: Frozen içeceklerin kıvamına göre Ø6mm - Ø12mm arası seçenekler mevcuttur. Milkshake ve frozen kokteyl için en az Ø8mm tercih edilmelidir.\n\nMalzeme Kalitesi: Gıda temasına uygun PP (polipropilen) malzeme kullanımı zorunludur. Lunar Ambalaj'ın tüm frozen pipet üretimi ISO 9001 sertifikalı tesislerde gerçekleştirilir.\n\nBaskı Seçenekleri: CMYK full color baskı ile marka logonuz ve renk kodlarınız %98 uyumla uygulanabilir. Minimum sipariş: 10,000 adet. Detaylı bilgi için /urunler/plastik-frozen-pipet sayfamızı ziyaret edebilirsiniz.\n\nSipariş Süreci: Baskı dosyası hazırlığı, proof onayı ve termin planlaması hakkında sıkça sorulan sorular için /sss sayfamızı inceleyebilirsiniz. Hızlı teklif almak için /teklif-al formunu doldurun.",
                'en_body' => "The most critical criteria when selecting plastic frozen straws are diameter size, durability, and print quality.\n\nDiameter Selection: Options range from Ø6mm to Ø12mm depending on beverage consistency. For milkshakes and frozen cocktails, at least Ø8mm is recommended.\n\nMaterial Quality: Food-grade PP (polypropylene) material is mandatory. All Lunar Packaging frozen straw production takes place in ISO 9001 certified facilities.\n\nPrint Options: CMYK full-color printing allows your brand logo and color codes to be applied with 98% accuracy. Minimum order: 10,000 units. For details, visit our /en/products/plastic-frozen-straw page.\n\nOrder Process: For FAQs about print file preparation, proof approval, and lead-time planning, check our /en/faq page. For a quick quote, fill out the /en/get-quote form.",
                'ru_body' => "??? ?????? ??????????? frozen-???????? ????????? ??????????? ???????? ???????, ????????? ????????? ? ???????????? ??????.

??????????? ????:
- ???????: ?6 ??, ?8 ??, ?10 ??, ?12 ??
- ?????: ???????? 21 ??, ?????????????? ????? ?? ???????
- ????????: ??????? PP
- ??????: CMYK, ???????, ????????? ?????
- MOQ: ?? 10 000 ??

???????? ??????????:
- ???? ? ???????? ????
- ????????? ? fast-food
- ???????? ???????? ? ?????-?????

???????????? ????????????:
??? ?????? ???????? (milkshake, frozen, smoothie) ????????? ??????? ?? ?8 ??. ??? ??????????? ???????? ???????? ?????? ?????????? ?6 ??.

???????????? ???:
???? ??? ????? ?????? ?? ????????, ?????? ? ?????, ????????? ?????? ????? /ru/get-quote. ????? ????? ???????? ?????? ?? ???????? ?? /ru/contact.",
                'ar_body' => "??? ?????? ???????? ??????????? ????????? ????????? ??? ??? ??????? ?? ?????? ????? ??????? ????? ???? ???????.

?????? ??????:
- ?????: ?6 ??? ?8 ??? ?10 ??? ?12 ??
- ?????: ????? 21 ?? ?? ??????? ??? ????
- ??????: PP ?????
- ???????: CMYK ?? ?????? ?????? ???????
- ???? ?????? ?????: ???? ?? 10,000 ????

????? ?????????:
- ??????? ?????? ??????
- ??????? ???????? ???????
- ??????? ???????? ??????? ?????????

????? ?????:
????????? ??????? ??? ?????? ??? ???????? ????????? ?????? ??? ?8 ?? ?????. ????????? ??????? ???????? ?????? ???? ?6 ??.

?????? ????????:
??? ??? ????? ??????? ??? ????? ???????? ???? ???????? ???? ???? ??? /ar/get-quote? ?????? ?????? ?????? ??????? ??? /ar/contact.",
            ],
            [
                'tr' => 'Çok amaçlı pipetlerde ölçü ve kullanım rehberi',
                'en' => 'Size and usage guide for multifunction straws',
                'ru' => 'Руководство по размерам и использованию многофункциональных трубочек',
                'ar' => 'دليل الحجم والاستخدام للمصاصات متعددة الوظائف',
                'tr_body' => "Çok amaçlı pipetler (multifunction straws), sıvı ve yoğun kıvamlı içecekler için optimize edilmiş ölçü seçenekleri sunar.\n\nBoy ve Çap: Standart 21cm boy, Ø6mm-Ø8mm çap aralığı en yaygın kullanım senaryolarını kapsar. Bardak ölçünüze göre özel boy üretimi yapılabilir.\n\nKullanım Alanları:\n- Kafe ve kahve zincirleri: soğuk içecek menüsü\n- Fast-food: combo menü standartlaştırması\n- Otel ve catering: premium servis sunumu\n\nBaskı ve Paketleme: Özel logolu baskı seçenekleri için /urunler/cok-amacli-pipet sayfamızdan detaylı bilgi alabilirsiniz. Tekli jelatinli paket seçeneği mevcuttur.\n\nSipariş Planlaması: MOQ (minimum order quantity) ve termin süreci hakkında detaylar için /iletisim sayfamızdan bizimle iletişime geçebilirsiniz.",
                'en_body' => "Multifunction straws offer size options optimized for both liquid and thick beverages.\n\nLength and Diameter: Standard 21cm length with Ø6mm-Ø8mm diameter range covers most use cases. Custom lengths can be produced according to your cup size.\n\nUse Cases:\n- Cafes and coffee chains: cold beverage menu\n- Fast-food: combo menu standardization\n- Hotels and catering: premium service presentation\n\nPrint and Packaging: For custom logo print options, visit our /en/products/multifunction-straw page for detailed information. Single-wrap gel options are available.\n\nOrder Planning: For details about MOQ (minimum order quantity) and lead time, contact us via /en/contact page.",
                'ru_body' => "??????????????????? ??????????? ???????? ???????? ????????????????? ?????? ? ?????? ???????? Horeca.

??????????? ????:
- ??????????? ?????: 21 ??
- ????????: ?6 ?? ? ?8 ??
- ?????????????? ???????: ???????? ??? ??????
- ??????: ???????, ???????? ???????, CMYK
- ????????: ???????? ??? ??????????????

??? ??????? ??????:
- ?6 ??: ????, ???????? ????, ?????????????? ???????
- ?8 ??: ????? ??????? ???????, ???????? ????????

???????????? ????????:
- ???? ?????? ? ?????? ?????????? ??????
- Fast-food ? ??????? ????????
- ????????? ? event-???????

???????????? ???:
????????? ?????? ??????? ? ???? ??? ??? ?????? ????? /ru/get-quote. ??? ??????? ?????? ????? ????????? ??????? ????? /ru/contact.",
                'ar_body' => "???????? ??????????? ?????? ??????????? ????? ??? ????? ???? ??????? ??? ????? ?????? ?? ???? ???????.

?????? ??????:
- ????? ???????: 21 ??
- ???????: ?6 ?? ??8 ??
- ???????? ??????: ????? ??? ???????
- ???????: ????? ????? ???????? CMYK
- ???????: ???? ?? ????

????? ?????? ??????:
- ?6 ??: ????? ??????? ??????? ?????????? ???????
- ?8 ??: ????????? ?????? ????? ??? ?????? ???

?????????? ???????:
- ????? ??????? ?????? ????? ?????
- ????? ??????? ??????? ??? ??????? ??????
- ????? ??????? ??????????

?????? ????????:
???? ????? ?????? ?????? ??????? ?????? ??? /ar/get-quote? ?????? ??? ????? ????? ??? /ar/contact.",
            ],
            [
                'tr' => 'B2B ambalaj tedarikinde MOQ yönetimi',
                'en' => 'MOQ management in B2B packaging supply',
                'ru' => 'Управление MOQ в поставках упаковки B2B',
                'ar' => 'إدارة الحد الأدنى للطلب في توريد التعبئة B2B',
                'tr_body' => "B2B ambalaj siparişlerinde minimum sipariş miktarı (MOQ) planlaması, maliyet ve stok optimizasyonu için kritik öneme sahiptir.\n\nLunar Ambalaj MOQ Standartları:\n- Plastik frozen pipet: 10,000 adet\n- Çok amaçlı pipet: 5,000 adet\n- Islak mendil (tekli paket): 20,000 adet\n- Stick şeker: 5,000 adet\n\nDetaylı MOQ bilgileri için /urunler sayfamızı ziyaret edebilirsiniz.\n\nKategori Kombinasyonu: Farklı kategorilerden ürünleri tek teklif dosyasında birleştirerek toplam sipariş miktarını optimize edebilirsiniz. Örneğin: pipet + bardak + peçete kombinasyonu.\n\nTermin Planlaması: MOQ'ya uygun siparişlerde 15 gün ortalama termin süresi geçerlidir. Ekspres termin seçenekleri için /teklif-al formundan özel talebinizi iletebilirsiniz.\n\nSıkça Sorulan Sorular: MOQ, baskı dosyaları ve fiyatlandırma hakkında detaylı bilgi için /sss sayfamızı inceleyebilirsiniz.",
                'en_body' => "Minimum order quantity (MOQ) planning in B2B packaging orders is critical for cost and inventory optimization.\n\nLunar Packaging MOQ Standards:\n- Plastic frozen straws: 10,000 units\n- Multifunction straws: 5,000 units\n- Wet wipes (single pack): 20,000 units\n- Stick sugar: 5,000 units\n\nFor detailed MOQ information, visit our /en/products page.\n\nCategory Combination: You can optimize total order volume by combining products from different categories in one quotation file. Example: straw + cup + napkin combination.\n\nLead Time Planning: Orders meeting MOQ have an average 15-day lead time. For express lead time options, submit your special request via /en/get-quote form.\n\nFAQs: For detailed information about MOQ, print files, and pricing, check our /en/faq page.",
                'ru_body' => "?????????? MOQ ? B2B-????????? ???????? ?????? ?? ?????????????, ????? ? ??????? ???????.

??????? ????????? MOQ:
- Plastic frozen straws: ?? 10 000 ??
- Multifunction straws: ?? 5 000 ??
- Single wet wipes: ?? 20 000 ??
- Stick sugar: ?? 5 000 ??

??? ??????? ???????? ?? ??????:
- ??????????? ????????? ? ???? ????? (???????? + ??????? + ????????)
- ?????????? ?????????? ???????? ?????? ??????? ???????
- ?????????????? ?????? ? ???????? ? ????? ?????

???????????? ??????:
??? ??????? ? ?????? MOQ ??????? ???? ?????????? ????? 15 ????; ??????? ??????? ????????? ????????.

???????????? ???:
????????? ????????? ?????? (?????????, ??????????, ??????, ????? ????????) ????? /ru/get-quote ? ?? ?????? ?????? ? ???? ???????? ? ??????? ????.",
                'ar_body' => "????? ???? ?????? ????? (MOQ) ?? ????? B2B ???? ?????? ??? ??????? ???????? ?????? ??????.

?????? MOQ ????????:
- ?????? ????????? ??????: ?? 10,000 ????
- ?????? ?????? ???????????: ?? 5,000 ????
- ?????? ????? ?????: ?? 20,000 ????
- ??? ????? ?????: ?? 5,000 ????

??? ????? ??? ?????????:
- ???? ???? ?? ??? ?? ??? ???? (?????? + ????? + ??????)
- ???? ?????? ????? ??? ??????? ????????
- ???? ??????? ??????? ???????? ?? ???? ????

????? ?????:
?? ??????? ??? ???? MOQ? ???? ????? ??? ??????? ??? 15 ?????? ???????? ??????? ?????? ???? ?????.

?????? ????????:
???? ???? ???? (?????? ??????? ???????? ????? ???????) ??? /ar/get-quote ??????? ?? ??????? ???? ????? ???? ??? ????.",
            ],
        ];

        foreach ($posts as $i => $postData) {
            $post = Post::query()->create([
                'cover' => 'images/catalog/asset-' . str_pad((string) (25 + $i), 2, '0', STR_PAD_LEFT) . '.jpg',
                'published_at' => now()->subDays($i + 1),
                'is_active' => true,
            ]);

            $trSlug = Str::slug($postData['tr']);
            $enSlug = Str::slug($postData['en']);

            $post->translations()->create([
                'lang' => 'tr',
                'title' => $postData['tr'],
                'slug' => $trSlug,
                'short_desc' => mb_substr(strip_tags($postData['tr_body']), 0, 150),
                'body' => $postData['tr_body'],
                'seo_title' => mb_substr($postData['tr'] . ' | Lunar Ambalaj Blog', 0, 60),
                'seo_desc' => mb_substr($postData['tr'] . ' - Detaylı rehber. MOQ, termin, baskı planlama bilgileri.', 0, 160),
            ]);

            $post->translations()->create([
                'lang' => 'en',
                'title' => $postData['en'],
                'slug' => $enSlug,
                'short_desc' => mb_substr(strip_tags($postData['en_body']), 0, 150),
                'body' => $postData['en_body'],
                'seo_title' => mb_substr($postData['en'] . ' | Lunar Packaging Blog', 0, 60),
                'seo_desc' => mb_substr($postData['en'] . ' - Detailed guide. MOQ, lead time, print planning info.', 0, 160),
            ]);

            // RU translation
            if (isset($postData['ru']) && isset($postData['ru_body'])) {
                $ruSlug = Str::slug($postData['en']); // Use EN slug for RU
                $post->translations()->create([
                    'lang' => 'ru',
                    'title' => $postData['ru'],
                    'slug' => $ruSlug,
                    'short_desc' => mb_substr(strip_tags($postData['ru_body']), 0, 150),
                    'body' => $postData['ru_body'],
                    'seo_title' => mb_substr($postData['ru'] . ' | Блог Lunar Packaging', 0, 60),
                    'seo_desc' => mb_substr($postData['ru'] . ' - Подробное руководство. MOQ, сроки, планирование печати.', 0, 160),
                ]);
            }

            // AR translation
            if (isset($postData['ar']) && isset($postData['ar_body'])) {
                $arSlug = Str::slug($postData['en']); // Use EN slug for AR
                $post->translations()->create([
                    'lang' => 'ar',
                    'title' => $postData['ar'],
                    'slug' => $arSlug,
                    'short_desc' => mb_substr(strip_tags($postData['ar_body']), 0, 150),
                    'body' => $postData['ar_body'],
                    'seo_title' => mb_substr($postData['ar'] . ' | مدونة Lunar Packaging', 0, 60),
                    'seo_desc' => mb_substr($postData['ar'] . ' - دليل تفصيلي. الحد الأدنى للطلب، المهلة، تخطيط الطباعة.', 0, 160),
                ]);
            }
        }

        $faqData = [
            ['tr' => 'Minimum sipariş nedir?', 'tr_a' => 'Varsayılan minimum sipariş 5000 adettir. Ürün bazında farklılık olabilir.', 'en' => 'What is the minimum order quantity?', 'en_a' => 'The default MOQ is 5000 units and can vary by product.'],
            ['tr' => 'Baskı dosyası formatı nasıl olmalı?', 'tr_a' => 'PDF, AI veya yüksek çözünürlüklü vektör formatları tercih edilir.', 'en' => 'What print file format is required?', 'en_a' => 'PDF, AI or high-resolution vector files are preferred.'],
            ['tr' => 'Termin kaç gün sürer?', 'tr_a' => 'Termin sipariş adedi ve ürün karmasına göre teklifte netleşir.', 'en' => 'How long is the lead time?', 'en_a' => 'Lead time is finalized in the quotation based on volume and product mix.'],
            ['tr' => 'Numune talep edebilir miyim?', 'tr_a' => 'Evet, ürün tipine göre numune süreci planlanabilir.', 'en' => 'Can I request samples?', 'en_a' => 'Yes, sample workflow can be arranged by product type.'],
            ['tr' => 'Jelatinli seçenek var mı?', 'tr_a' => 'Pipet ve ıslak mendil gruplarında tekli paketleme seçenekleri sunulur.', 'en' => 'Do you provide wrapped options?', 'en_a' => 'Single-wrap options are available in straw and wet wipe groups.'],
            ['tr' => 'Plastik frozen pipette hangi ölçüler var?', 'tr_a' => 'Çap ve boy seçenekleri talebe göre mm bazında planlanır.', 'en' => 'What sizes are available for plastic frozen straws?', 'en_a' => 'Diameter and length options are planned in mm based on your request.'],
            ['tr' => 'Özel ölçü pipet üretimi yapılır mı?', 'tr_a' => 'Evet, proje bazlı özel ölçü ve baskı kombinasyonları üretilebilir.', 'en' => 'Do you offer custom-size straw production?', 'en_a' => 'Yes, project-based custom size and print combinations are available.'],
            ['tr' => 'Kağıt pipet üretiyor musunuz?', 'tr_a' => 'Kağıt pipet üretimi fason modelde ve talebe bağlı olarak sağlanır.', 'en' => 'Do you produce paper straws?', 'en_a' => 'Paper straws are supplied in contract manufacturing mode on demand.'],
            ['tr' => 'Bayraklı kürdan baskısı tek veya çift yüz olabilir mi?', 'tr_a' => 'Evet, tasarıma göre tek veya çift yüz baskı uygulanabilir.', 'en' => 'Can flag toothpicks be single or double side printed?', 'en_a' => 'Yes, single or double side print can be applied depending on design.'],
            ['tr' => 'Stick şeker ambalajında logo baskı yapılıyor mu?', 'tr_a' => 'Evet, marka renklerine uygun logo baskılı stick şeker üretimi yapılır.', 'en' => 'Do you print logos on stick sugar packaging?', 'en_a' => 'Yes, we provide logo printed stick sugar packaging in brand colors.'],
            ['tr' => 'Koli içi adet bilgisi nasıl belirlenir?', 'tr_a' => 'Ürün ölçüsü ve paketleme seçeneğine göre koli içi adet teklifte belirtilir.', 'en' => 'How is carton quantity determined?', 'en_a' => 'Carton quantity is defined in quote according to product size and packaging option.'],
            ['tr' => 'Yurt dışına gönderim yapıyor musunuz?', 'tr_a' => 'İhracat sürecinde teslim şekli ve dokümantasyon bilgisi teklifte sunulur.', 'en' => 'Do you ship internationally?', 'en_a' => 'Delivery method and export documentation are clarified during quotation.'],
        ];

        $faqRuAr = [
            ['ru' => 'Каков минимальный объем заказа?', 'ru_a' => 'Базовый MOQ начинается от 5000 шт. Для некоторых категорий (например, frozen-трубочки) он может быть выше.', 'ar' => 'ما هو الحد الأدنى للطلب؟', 'ar_a' => 'الحد الأدنى الأساسي يبدأ من 5000 قطعة، وقد يرتفع حسب الفئة مثل المصاصات المجمدة.'],
            ['ru' => 'Какой формат файла печати требуется?', 'ru_a' => 'Рекомендуем PDF/AI/EPS в векторе. Шрифты переведите в кривые и укажите цвета CMYK.', 'ar' => 'ما صيغة ملف الطباعة المطلوبة؟', 'ar_a' => 'نوصي بملفات PDF أو AI أو EPS بصيغة متجهة، مع تحويل الخطوط إلى منحنيات واعتماد ألوان CMYK.'],
            ['ru' => 'Какой стандартный срок производства?', 'ru_a' => 'Средний срок 15 дней после подтверждения макета и условий заказа. Срочные проекты считаются отдельно.', 'ar' => 'ما مدة التنفيذ القياسية؟', 'ar_a' => 'متوسط التنفيذ 15 يومًا بعد اعتماد التصميم وشروط الطلب، ويمكن احتساب المشاريع العاجلة بشكل منفصل.'],
            ['ru' => 'Можно ли получить образцы?', 'ru_a' => 'Да. По типу продукта и объему проекта формируем образец и согласование до запуска серии.', 'ar' => 'هل يمكن طلب عينات؟', 'ar_a' => 'نعم، نوفّر عينات حسب نوع المنتج وحجم المشروع قبل بدء الإنتاج الكمي.'],
            ['ru' => 'Есть ли индивидуальная упаковка?', 'ru_a' => 'Да, доступны single-wrap решения для трубочек и влажных салфеток в зависимости от проекта.', 'ar' => 'هل يتوفر تغليف فردي؟', 'ar_a' => 'نعم، يتوفر التغليف الفردي لبعض المنتجات مثل المصاصات والمناديل المبللة حسب متطلبات المشروع.'],
            ['ru' => 'Какие размеры доступны для frozen-трубочек?', 'ru_a' => 'Стандартно Ø6-Ø12 мм, длина под формат стакана. Точный подбор делаем по типу напитка.', 'ar' => 'ما المقاسات المتاحة للمصاصات المجمدة؟', 'ar_a' => 'المتاح عادة من Ø6 إلى Ø12 مم مع أطوال مناسبة لحجم الكوب، ويتم الاختيار حسب نوع المشروب.'],
            ['ru' => 'Доступны ли нестандартные размеры?', 'ru_a' => 'Да, производим по проектным размерам с учетом печати, упаковки и MOQ.', 'ar' => 'هل تتوفر مقاسات مخصصة؟', 'ar_a' => 'نعم، نوفر إنتاجًا بمقاسات خاصة مع مراعاة الطباعة والتغليف والحد الأدنى للطلب.'],
            ['ru' => 'Производите ли вы бумажные трубочки?', 'ru_a' => 'Да, бумажные трубочки доступны в формате контрактного производства по запросу.', 'ar' => 'هل تنتجون مصاصات ورقية؟', 'ar_a' => 'نعم، تتوفر المصاصات الورقية بصيغة تصنيع تعاقدي عند الطلب.'],
            ['ru' => 'Можно ли печатать флажковые зубочистки с двух сторон?', 'ru_a' => 'Да, доступна односторонняя и двусторонняя печать по макету.', 'ar' => 'هل يمكن طباعة أعواد الأسنان بالأعلام من جانبين؟', 'ar_a' => 'نعم، تتوفر طباعة من جانب واحد أو من الجانبين حسب التصميم المعتمد.'],
            ['ru' => 'Печатаете ли вы логотип на stick sugar?', 'ru_a' => 'Да, выполняем брендированную упаковку stick sugar в цветах бренда.', 'ar' => 'هل يمكن طباعة الشعار على سكر الأكياس العصوية؟', 'ar_a' => 'نعم، نوفر طباعة شعار العلامة على تغليف سكر الأكياس العصوية بألوان الهوية.'],
            ['ru' => 'Как определяется количество в коробке?', 'ru_a' => 'Количество зависит от размера изделия и типа упаковки. Значение фиксируется в коммерческом предложении.', 'ar' => 'كيف يتم تحديد عدد القطع في الكرتون؟', 'ar_a' => 'يتحدد العدد حسب مقاس المنتج ونوع التغليف، ويتم تثبيته في عرض السعر.'],
            ['ru' => 'Возможна ли международная отправка?', 'ru_a' => 'Да, экспорт возможен. Формат поставки и пакет документов уточняются на этапе расчета.', 'ar' => 'هل يتوفر شحن دولي؟', 'ar_a' => 'نعم، الشحن الدولي متاح، ويتم تحديد طريقة التسليم ووثائق التصدير أثناء إعداد العرض.'],
        ];

        $faqCategories = ['general', 'order', 'shipment', 'technical'];

        foreach ($faqData as $i => $faqItem) {
            $faq = Faq::query()->create([
                'category' => $faqCategories[$i % count($faqCategories)],
                'order' => $i + 1,
                'is_active' => true,
            ]);
            $faq->translations()->create(['lang' => 'tr', 'question' => $faqItem['tr'], 'answer' => $faqItem['tr_a']]);
            $faq->translations()->create(['lang' => 'en', 'question' => $faqItem['en'], 'answer' => $faqItem['en_a']]);

            // Add RU and AR translations
            if (isset($faqRuAr[$i])) {
                $faq->translations()->create(['lang' => 'ru', 'question' => $faqRuAr[$i]['ru'], 'answer' => $faqRuAr[$i]['ru_a']]);
                $faq->translations()->create(['lang' => 'ar', 'question' => $faqRuAr[$i]['ar'], 'answer' => $faqRuAr[$i]['ar_a']]);
            }
        }

        for ($i = 1; $i <= 8; $i++) {
            Reference::query()->create(['logo' => null, 'url' => null, 'order' => $i, 'is_active' => true]);
        }

        $testimonials = [
            [
                'author' => 'Mehmet Yılmaz',
                'position' => 'Satın Alma Müdürü',
                'company' => 'Kahve Dünyası',
                'rating' => 5,
                'tr' => 'Lunar Ambalaj ile çalışmaya başladığımızdan beri tedarik sürecimiz çok hızlandı. Özellikle baskılı pipet siparişlerinde kalite ve teslimat süresi beklentilerimizi fazlasıyla karşılıyor. Tüm şubelerimiz için tek tedarikçi modeliyle çalışmak operasyonlarımızı kolaylaştırdı.',
                'en' => 'Since we started working with Lunar Packaging, our supply process has accelerated significantly. The quality and delivery time for printed straw orders exceed our expectations. Working with a single supplier model for all our branches has simplified our operations.',
                'ru' => 'С тех пор как мы начали работать с Lunar Packaging, наш процесс поставок значительно ускорился. Качество и сроки доставки заказов на печатные трубочки превосходят наши ожидания.',
                'ar' => 'منذ أن بدأنا العمل مع Lunar Packaging، تسارعت عملية التوريد لدينا بشكل ملحوظ. الجودة ووقت التسليم لطلبات المصاصات المطبوعة تتجاوز توقعاتنا.',
            ],
            [
                'author' => 'Ayşe Demir',
                'position' => 'Operasyon Direktörü',
                'company' => 'FastBite Restoranlar',
                'rating' => 5,
                'tr' => '30 şubemiz için pipet, bardak ve peçete ihtiyaçlarımızı tek firmadan karşılamak maliyetleri düşürdü. Minimum sipariş miktarları makul, fiyatlar rekabetçi ve termin süreleri güvenilir. Ekip çok profesyonel ve duyarlı.',
                'en' => 'Meeting our straw, cup and napkin needs for 30 branches from a single supplier reduced costs. Minimum order quantities are reasonable, prices competitive and lead times reliable. The team is very professional and responsive.',
                'ru' => 'Удовлетворение потребностей в трубочках, стаканах и салфетках для 30 филиалов от одного поставщика снизило затраты. Минимальные объемы заказа разумны, цены конкурентоспособны.',
                'ar' => 'تلبية احتياجات المصاصات والأكواب والمناديل لـ 30 فرعًا من مورد واحد خفض التكاليف. كميات الطلب الدنيا معقولة والأسعار تنافسية.',
            ],
            [
                'author' => 'Can Öztürk',
                'position' => 'Satın Alma Müdürü',
                'company' => 'Grand Otel Zinciri',
                'rating' => 5,
                'tr' => 'Otellerimizin tüm lokasyonları için özel baskılı ıslak mendil ve pipet tedarikinde Lunar Ambalaj\'ı tercih ediyoruz. Ürün kalitesi premium, siparişler zamanında teslim ediliyor. Müşteri hizmetleri her zaman yanıt veriyor.',
                'en' => 'We prefer Lunar Packaging for custom printed wet wipes and straw supply for all our hotel locations. Product quality is premium, orders are delivered on time. Customer service is always responsive.',
                'ru' => 'Мы предпочитаем Lunar Packaging для поставки влажных салфеток и трубочек с индивидуальной печатью для всех наших отелей. Качество продукции премиальное, заказы доставляются вовремя.',
                'ar' => 'نفضل Lunar Packaging لتوريد المناديل المبللة والمصاصات المطبوعة حسب الطلب لجميع مواقع فنادقنا. جودة المنتج ممتازة والطلبات تسلم في الوقت المحدد.',
            ],
            [
                'author' => 'Elif Kaya',
                'position' => 'Genel Müdür',
                'company' => 'Elite Catering',
                'rating' => 5,
                'tr' => 'Etkinlik ve organizasyonlarımızda özel baskılı peçete, bardak ve bayraklı kürdan kullanıyoruz. Lunar Ambalaj her projede zamanında teslimat ve esnek stok yönetimi sağlıyor. Markamızı güçlendiren özel baskı kalitesinden çok memnunuz.',
                'en' => 'We use custom printed napkins, cups and flag toothpicks in our events and organizations. Lunar Packaging provides on-time delivery and flexible stock management in every project. We are very satisfied with the custom print quality that strengthens our brand.',
                'ru' => 'Мы используем салфетки, стаканы и флажки с индивидуальной печатью на наших мероприятиях. Lunar Packaging обеспечивает своевременную доставку и гибкое управление запасами в каждом проекте.',
                'ar' => 'نستخدم المناديل والأكواب وأعواد الأسنان المطبوعة حسب الطلب في فعالياتنا. توفر Lunar Packaging التسليم في الوقت المحدد وإدارة مرنة للمخزون في كل مشروع.',
            ],
            [
                'author' => 'Murat Şahin',
                'position' => 'İşletme Sahibi',
                'company' => 'FrozenLove Dondurma',
                'rating' => 5,
                'tr' => 'Frozen pipet ihtiyaçlarımızı karşılayan en güvenilir tedarikçi. Geniş çap seçenekleri, stabil kalite ve her siparişte tutarlı performans. 5 yıldır çalışıyoruz, hiç sorun yaşamadık. Dondurma salonları için kesinlikle tavsiye ederim.',
                'en' => 'The most reliable supplier meeting our frozen straw needs. Wide diameter options, stable quality and consistent performance in every order. We have been working for 5 years with no issues. I definitely recommend it for ice cream parlors.',
                'ru' => 'Самый надежный поставщик, удовлетворяющий наши потребности в трубочках для замороженных продуктов. Широкий выбор диаметров, стабильное качество. Работаем 5 лет без проблем.',
                'ar' => 'أكثر المورّدين موثوقية لتلبية احتياجاتنا من المصاصات المجمدة. خيارات واسعة للقطر وجودة مستقرة. نعمل منذ 5 سنوات بدون مشاكل.',
            ],
            [
                'author' => 'Zeynep Arslan',
                'position' => 'Etkinlik Koordinatörü',
                'company' => 'BlueSky Events',
                'rating' => 5,
                'tr' => 'Kurumsal etkinliklerde özel baskılı ürünler kullanıyoruz. Lunar Ambalaj her seferinde beklentilerimizi karşılıyor. Hızlı teklif, esnek minimum sipariş ve mükemmel baskı kalitesi. Ekip her proje için çözüm odaklı yaklaşıyor.',
                'en' => 'We use custom printed products in corporate events. Lunar Packaging meets our expectations every time. Fast quotation, flexible minimum order and excellent print quality. The team approaches each project with a solution-focused mindset.',
                'ru' => 'Мы используем продукцию с индивидуальной печатью на корпоративных мероприятиях. Lunar Packaging каждый раз оправдывает наши ожидания. Быстрое предложение, гибкий минимальный заказ и отличное качество печати.',
                'ar' => 'نستخدم المنتجات المطبوعة حسب الطلب في الفعاليات المؤسسية. تلبي Lunar Packaging توقعاتنا في كل مرة. عرض سعر سريع وحد أدنى مرن للطلب وجودة طباعة ممتازة.',
            ],
            [
                'author' => 'Ahmet Çelik',
                'position' => 'Restoran Müdürü',
                'company' => 'Lezzet Sofrası',
                'rating' => 5,
                'tr' => 'Tek kullanımlık ürünlerimizin tamamını Lunar Ambalaj\'dan temin ediyoruz. Islak mendil, peçete, pipet ve stick şeker siparişlerinde hiç aksamadılar. Fiyat-kalite dengesinde sektörün en iyisi. Güvenle çalıştığımız bir tedarikçi.',
                'en' => 'We source all our disposable products from Lunar Packaging. They never failed on wet wipes, napkins, straws and stick sugar orders. The best in the industry for price-quality balance. A supplier we work with confidently.',
                'ru' => 'Мы закупаем все одноразовые товары у Lunar Packaging. Они никогда не подводили с заказами влажных салфеток, салфеток, трубочек и сахара в стиках. Лучшие в отрасли по соотношению цена-качество.',
                'ar' => 'نحصل على جميع منتجاتنا ذات الاستخدام الواحد من Lunar Packaging. لم يخذلونا أبدًا في طلبات المناديل المبللة والمناديل والمصاصات والسكر. الأفضل في الصناعة من حيث التوازن بين السعر والجودة.',
            ],
            [
                'author' => 'Deniz Yıldız',
                'position' => 'Üretim Müdürü',
                'company' => 'GoldenBake Fırın',
                'rating' => 5,
                'tr' => 'Pastane ve fırın zinciri olarak stick şeker ve baskılı peçete ihtiyaçlarımızı karşılıyorlar. Siparişlerimiz her zaman zamanında teslim ediliyor, ürün kalitesi tutarlı. Müşteri hizmetleri ekibi her türlü soruya hızlı yanıt veriyor.',
                'en' => 'As a bakery and patisserie chain, they meet our stick sugar and printed napkin needs. Our orders are always delivered on time, product quality is consistent. Customer service team responds quickly to all questions.',
                'ru' => 'Как сеть пекарен и кондитерских, они удовлетворяют наши потребности в сахаре в стиках и печатных салфетках. Наши заказы всегда доставляются вовремя, качество продукции стабильное.',
                'ar' => 'كسلسلة مخابز وحلويات، يلبون احتياجاتنا من السكر والمناديل المطبوعة. يتم تسليم طلباتنا دائمًا في الوقت المحدد، وجودة المنتج متسقة.',
            ],
        ];

        foreach ($testimonials as $i => $testimonialData) {
            $testimonial = Testimonial::query()->create([
                'author_name' => $testimonialData['author'],
                'author_position' => $testimonialData['position'],
                'company_name' => $testimonialData['company'],
                'company_logo' => null,
                'rating' => $testimonialData['rating'],
                'order' => $i + 1,
                'is_active' => true,
            ]);

            $testimonial->translations()->create(['lang' => 'tr', 'content' => $testimonialData['tr']]);
            $testimonial->translations()->create(['lang' => 'en', 'content' => $testimonialData['en']]);
            $testimonial->translations()->create(['lang' => 'ru', 'content' => $testimonialData['ru']]);
            $testimonial->translations()->create(['lang' => 'ar', 'content' => $testimonialData['ar']]);
        }

        Setting::query()->updateOrCreate(['id' => 1], [
            'phone' => '+90 542 168 84 78',
            'email' => 'info@lunarambalaj.com.tr',
            'email_secondary' => 'lunarambalaj@gmail.com',
            'address' => 'Yenidoğan Mah. Bahçelievler Cad. No: 2 Kat: 2 D: 35 Plaza Katı, 34652 Sancaktepe / İstanbul',
            'working_hours' => "Pazartesi - Cuma 09:00 - 18:00\nCumartesi Pazar: Kapalı",
            'whatsapp' => '905421688478',
            'facebook' => 'https://www.facebook.com',
            'instagram' => 'https://instagram.com',
            'linkedin' => 'https://tr.linkedin.com/',
            'min_order_default' => 5000,
            'company_name_tr' => 'Lunar Ambalaj',
            'company_name_en' => 'Lunar Packaging',
            'hero_h1_tr' => 'Plastik Frozen ve Özel Ölçü Pipet Üretiminde Tek Üretici, Tek Çözüm',
            'hero_h1_en' => 'One Manufacturer for Plastic Frozen and Custom Size Straws',
            'hero_subtitle_tr' => 'Plastik frozen, çok amaçlı ve özel ölçü pipet üretiminde markanıza özel baskı seçenekleri. Kağıt pipet üretimi talebe bağlı fason modelde sağlanır.',
            'hero_subtitle_en' => 'Custom printing options for plastic frozen, multifunction and custom-size straws. Paper straws are available via contract manufacturing on demand.',
            'footer_short_tr' => 'İstanbul merkezli üretim, Türkiye geneli sevkiyat ve ihracata uygun B2B tedarik.',
            'footer_short_en' => 'Istanbul-based production with nationwide shipment and export-ready B2B supply.',
        ]);

        $trackingEvents = [
            ['event_key' => 'lead_submit', 'display_name' => 'Lead Submit', 'schema' => ['lead_type' => 'quote|contact', 'product_category' => 'string', 'quantity' => 'number']],
            ['event_key' => 'click_phone', 'display_name' => 'Click Phone', 'schema' => ['phone' => 'string', 'location' => 'header|footer|contact']],
            ['event_key' => 'click_whatsapp', 'display_name' => 'Click WhatsApp', 'schema' => ['location' => 'header|footer|floating']],
            ['event_key' => 'click_quote_cta', 'display_name' => 'Click Quote CTA', 'schema' => ['page_path' => 'string']],
            ['event_key' => 'view_item', 'display_name' => 'View Item', 'schema' => ['item_id' => 'string', 'item_name' => 'string', 'locale' => 'string']],
            ['event_key' => 'view_item_list', 'display_name' => 'View Item List', 'schema' => ['item_list_name' => 'string', 'item_count' => 'number']],
        ];

        foreach ($trackingEvents as $event) {
            TrackingEvent::query()->updateOrCreate(
                ['event_key' => $event['event_key']],
                ['display_name' => $event['display_name'], 'schema' => $event['schema'], 'is_active' => true],
            );
        }

        foreach (['google_ads', 'meta_ads', 'ga4', 'gtm'] as $platform) {
            AdIntegration::query()->updateOrCreate(
                ['platform' => $platform],
                ['name' => strtoupper($platform), 'credentials' => [], 'is_active' => false],
            );
        }

        $mappingDefaults = [
            ['platform' => 'google_ads', 'event_key' => 'lead_submit', 'dedup_key' => 'lead_id'],
            ['platform' => 'meta_ads', 'event_key' => 'lead_submit', 'dedup_key' => 'lead_id'],
            ['platform' => 'meta_ads', 'event_key' => 'view_item', 'dedup_key' => 'content_id'],
            ['platform' => 'ga4', 'event_key' => 'click_phone', 'dedup_key' => 'session_id'],
            ['platform' => 'ga4', 'event_key' => 'click_whatsapp', 'dedup_key' => 'session_id'],
            ['platform' => 'ga4', 'event_key' => 'click_quote_cta', 'dedup_key' => 'session_id'],
        ];

        foreach ($mappingDefaults as $mapping) {
            $event = TrackingEvent::query()->where('event_key', $mapping['event_key'])->first();
            if (! $event) {
                continue;
            }

            ConversionMapping::query()->updateOrCreate(
                ['platform' => $mapping['platform'], 'tracking_event_id' => $event->id],
                ['dedup_key' => $mapping['dedup_key'], 'is_active' => true],
            );
        }

        $this->call(LegalPolicyContentSeeder::class);
        $this->call(CustomerCatalogRevisionSeeder::class);
    }

    private function resetSeedTablesForLocal(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            return;
        }

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        } elseif (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        }

        $tables = [
            'event_logs',
            'attribution_logs',
            'campaign_snapshots',
            'conversion_mappings',
            'tracking_events',
            'ad_integrations',
            'report_caches',
            'testimonial_translations',
            'testimonials',
            'case_study_translations',
            'case_studies',
            'post_translations',
            'posts',
            'product_translations',
            'products',
            'product_category_translations',
            'product_categories',
            'faq_translations',
            'faqs',
            'service_item_translations',
            'service_items',
            'page_translations',
            'pages',
            'references',
            'settings',
            'languages',
            'leads',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->delete();
            }
        }

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        } elseif (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
