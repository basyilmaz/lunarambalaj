<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTranslation;
use App\Models\ServiceItem;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class CustomerCatalogRevisionSeeder extends Seeder
{
    private const LOCALES = ['tr', 'en', 'ru', 'ar'];

    public function run(): void
    {
        $categories = $this->upsertCategories();
        $this->upsertServiceVisuals();
        $this->upsertCatalog($categories);
        $this->deactivateDeprecatedVariants();
        $this->updateBlogMeasurements();
        $this->updateHeroSettings();
    }

    /**
     * @return array<string, ProductCategory>
     */
    private function upsertCategories(): array
    {
        $definitions = [
            'pipet' => ['order' => 1, 'tr' => ['name' => 'Pipet', 'slug' => 'pipet'], 'en' => ['name' => 'Straws', 'slug' => 'straws'], 'ru' => ['name' => 'Трубочки', 'slug' => 'straws'], 'ar' => ['name' => 'المصاصات', 'slug' => 'straws']],
            'bardak' => ['order' => 2, 'tr' => ['name' => 'Bardak', 'slug' => 'bardak'], 'en' => ['name' => 'Cups', 'slug' => 'cups'], 'ru' => ['name' => 'Стаканы', 'slug' => 'cups'], 'ar' => ['name' => 'الأكواب', 'slug' => 'cups']],
            'pecete' => ['order' => 3, 'tr' => ['name' => 'Peçete', 'slug' => 'pecete'], 'en' => ['name' => 'Napkins', 'slug' => 'napkins'], 'ru' => ['name' => 'Салфетки', 'slug' => 'napkins'], 'ar' => ['name' => 'المناديل', 'slug' => 'napkins']],
            'islak-mendil' => ['order' => 4, 'tr' => ['name' => 'Islak Mendil', 'slug' => 'islak-mendil'], 'en' => ['name' => 'Wet Wipes', 'slug' => 'wet-wipes'], 'ru' => ['name' => 'Влажные салфетки', 'slug' => 'wet-wipes'], 'ar' => ['name' => 'المناديل المبللة', 'slug' => 'wet-wipes']],
            'bayrakli-kurdan' => ['order' => 5, 'tr' => ['name' => 'Bayraklı Kürdan', 'slug' => 'bayrakli-kurdan'], 'en' => ['name' => 'Flag Toothpicks', 'slug' => 'flag-toothpicks'], 'ru' => ['name' => 'Флажковые зубочистки', 'slug' => 'flag-toothpicks'], 'ar' => ['name' => 'أعواد الأسنان بالأعلام', 'slug' => 'flag-toothpicks']],
            'stick-seker' => ['order' => 6, 'tr' => ['name' => 'Stick Şeker', 'slug' => 'stick-seker'], 'en' => ['name' => 'Stick Sugar', 'slug' => 'stick-sugar'], 'ru' => ['name' => 'Стик-сахар', 'slug' => 'stick-sugar'], 'ar' => ['name' => 'السكر الساشيه', 'slug' => 'stick-sugar']],
            'sticker' => ['order' => 7, 'tr' => ['name' => 'Sticker & Etiket', 'slug' => 'etiket-sticker'], 'en' => ['name' => 'Sticker & Labels', 'slug' => 'sticker-labels'], 'ru' => ['name' => 'Стикеры и этикетки', 'slug' => 'sticker-labels'], 'ar' => ['name' => 'الستيكرات والملصقات', 'slug' => 'sticker-labels']],
        ];

        $map = [];
        foreach ($definitions as $key => $definition) {
            $category = ProductCategory::query()->whereHas('translations', function ($query) use ($definition): void {
                $query->where('lang', 'tr')->where('slug', $definition['tr']['slug']);
            })->first();

            if (! $category) {
                $category = ProductCategory::query()->create(['order' => $definition['order'], 'is_active' => true]);
            }

            $category->update(['order' => $definition['order'], 'is_active' => true]);
            foreach (self::LOCALES as $locale) {
                $category->translations()->updateOrCreate(['lang' => $locale], $definition[$locale]);
            }
            $map[$key] = $category;
        }

        return $map;
    }

    private function upsertServiceVisuals(): void
    {
        $images = [
            1 => 'images/catalog/asset-07.png',
            2 => 'images/catalog/asset-11.jpg',
            3 => 'images/catalog/asset-17.jpg',
            4 => 'images/catalog/asset-14.jpg',
            5 => 'images/catalog/asset-20.jpg',
            6 => 'images/catalog/asset-22.jpg',
        ];

        foreach ($images as $order => $image) {
            ServiceItem::query()->where('order', $order)->update(['icon' => $image, 'is_active' => true]);
        }
    }
    /**
     * @param array<string, ProductCategory> $categories
     */
    private function upsertCatalog(array $categories): void
    {
        $items = [
            [
                'lookup' => ['plastic-frozen-straws'],
                'category' => 'pipet',
                'image' => 'images/catalog/asset-07.png',
                'min_order' => 5000,
                'print' => true,
                'wrap' => true,
                'lead' => 20,
                'specs' => [
                    'tr' => ['Ölçü' => '6-12 mm / 24 cm', 'Baskı' => 'Baskılı / baskısız', 'Paketleme' => 'Jelatinli/jelatinsiz 250’li', 'Min. Sipariş' => '5.000 adet', 'Termin' => '20 iş günü'],
                    'en' => ['Size' => '6-12 mm / 24 cm', 'Printing' => 'Printed / plain', 'Packaging' => 'Wrapped/unwrapped packs of 250', 'Min. Order' => '5,000 units', 'Lead Time' => '20 business days'],
                    'ru' => ['Размер' => '6-12 мм / 24 см', 'Печать' => 'С печатью / без', 'Упаковка' => 'Упаковка по 250', 'Мин. заказ' => '5 000 шт', 'Срок' => '20 рабочих дней'],
                    'ar' => ['المقاس' => '6-12 مم / 24 سم', 'الطباعة' => 'مطبوع / غير مطبوع', 'التغليف' => 'عبوات 250 مغلف/غير مغلف', 'الحد الأدنى' => '5,000 قطعة', 'مدة التنفيذ' => '20 يوم عمل'],
                ],
                'translations' => [
                    'tr' => ['name' => 'Plastik Frozen Pipet', 'slug' => 'plastik-frozen-pipet', 'short' => 'Baskılı/baskısız frozen pipet üretimi.', 'desc' => 'Frozen içecekler için 6-12 mm çapta plastik pipet üretimi yapılır. Jelatinli veya jelatinsiz 250’li paketleme sunulur.'],
                    'en' => ['name' => 'Plastic Frozen Straws', 'slug' => 'plastic-frozen-straws', 'short' => 'Printed/plain frozen straw production.', 'desc' => 'Plastic frozen straws are produced in 6-12 mm diameters. Wrapped or unwrapped packs of 250 are available.'],
                    'ru' => ['name' => 'Пластиковые трубочки Frozen', 'slug' => 'plastic-frozen-straws', 'short' => 'Производство frozen-трубочек с печатью и без.', 'desc' => 'Производим пластиковые frozen-трубочки диаметром 6-12 мм. Упаковка по 250 шт, в индивидуальной упаковке или без нее.'],
                    'ar' => ['name' => 'مصاصات بلاستيكية للمشروبات المجمّدة', 'slug' => 'plastic-frozen-straws', 'short' => 'إنتاج مصاصات فروزن مطبوعة وغير مطبوعة.', 'desc' => 'ننتج مصاصات بلاستيكية للفروزن بقطر 6-12 مم مع خيار التغليف أو بدون تغليف ضمن عبوات 250 قطعة.'],
                ],
            ],
            [
                'lookup' => ['multifunction-plastic-straws'],
                'category' => 'pipet',
                'image' => 'images/catalog/asset-08.png',
                'min_order' => 5000,
                'print' => true,
                'wrap' => true,
                'lead' => 20,
                'specs' => [
                    'tr' => ['Model' => 'Körüklü pipet', 'Baskı' => 'Baskılı / baskısız', 'Paketleme' => 'Jelatinli/jelatinsiz 250’li', 'Min. Sipariş' => '5.000 adet', 'Termin' => '20 iş günü'],
                    'en' => ['Model' => 'Corrugated straws', 'Printing' => 'Printed/plain', 'Packaging' => 'Packs of 250', 'Min. Order' => '5,000 units', 'Lead Time' => '20 business days'],
                    'ru' => ['Модель' => 'Гофрированные трубочки', 'Печать' => 'С печатью / без', 'Упаковка' => 'По 250 шт', 'Мин. заказ' => '5 000 шт', 'Срок' => '20 рабочих дней'],
                    'ar' => ['الموديل' => 'مصاصات مرنة', 'الطباعة' => 'مطبوع / غير مطبوع', 'التغليف' => 'عبوات 250', 'الحد الأدنى' => '5,000 قطعة', 'مدة التنفيذ' => '20 يوم عمل'],
                ],
                'translations' => [
                    'tr' => ['name' => 'Körüklü Pipet', 'slug' => 'koruklu-pipet', 'short' => 'Baskılı/baskısız körüklü pipet üretimi.', 'desc' => 'Körüklü pipet ürününde baskılı ve baskısız seçenekler sunulur. Paketleme 250’li, jelatinli veya jelatinsiz olarak planlanır.'],
                    'en' => ['name' => 'Corrugated Straws', 'slug' => 'corrugated-straws', 'short' => 'Printed/plain corrugated straws.', 'desc' => 'Corrugated straws are available in printed and plain options with wrapped or unwrapped packs of 250.'],
                    'ru' => ['name' => 'Гофрированные трубочки', 'slug' => 'corrugated-straws', 'short' => 'Гофрированные трубочки с печатью и без.', 'desc' => 'Доступны гофрированные трубочки с печатью и без печати, упаковка по 250 шт.'],
                    'ar' => ['name' => 'مصاصات مرنة', 'slug' => 'corrugated-straws', 'short' => 'مصاصات مرنة مطبوعة أو غير مطبوعة.', 'desc' => 'نوفر مصاصات مرنة بخيارات مطبوعة وغير مطبوعة مع تغليف أو بدون تغليف ضمن عبوات 250 قطعة.'],
                ],
            ],
            [
                'lookup' => ['custom-size-plastic-straws'],
                'category' => 'pipet',
                'image' => 'images/catalog/asset-09.jpg',
                'min_order' => 2500,
                'print' => true,
                'wrap' => true,
                'lead' => 20,
                'specs' => [
                    'tr' => ['Model' => 'Bubble pipet', 'Baskı' => 'Baskılı / baskısız', 'Paketleme' => 'Jelatinli/jelatinsiz 100’lü', 'Min. Sipariş' => '2.500 adet', 'Termin' => '20 iş günü'],
                    'en' => ['Model' => 'Bubble straws', 'Printing' => 'Printed/plain', 'Packaging' => 'Packs of 100', 'Min. Order' => '2,500 units', 'Lead Time' => '20 business days'],
                    'ru' => ['Модель' => 'Bubble-трубочки', 'Печать' => 'С печатью / без', 'Упаковка' => 'По 100 шт', 'Мин. заказ' => '2 500 шт', 'Срок' => '20 рабочих дней'],
                    'ar' => ['الموديل' => 'مصاصات بابل', 'الطباعة' => 'مطبوع / غير مطبوع', 'التغليف' => 'عبوات 100', 'الحد الأدنى' => '2,500 قطعة', 'مدة التنفيذ' => '20 يوم عمل'],
                ],
                'translations' => [
                    'tr' => ['name' => 'Bubble Pipet', 'slug' => 'bubble-pipet', 'short' => 'Özel ölçü bubble pipet üretimi.', 'desc' => 'Bubble pipet ürününde özel ölçü üretim yapılır. Paketleme 100’lü jelatinli/jelatinsiz sunulur.'],
                    'en' => ['name' => 'Bubble Straws', 'slug' => 'bubble-straws', 'short' => 'Custom-size bubble straw production.', 'desc' => 'Bubble straws are produced in custom sizes with printed/plain options and packs of 100.'],
                    'ru' => ['name' => 'Bubble-трубочки', 'slug' => 'bubble-straws', 'short' => 'Производство bubble-трубочек под размер.', 'desc' => 'Bubble-трубочки производятся по проектным размерам, упаковка по 100 шт.'],
                    'ar' => ['name' => 'مصاصات بابل', 'slug' => 'bubble-straws', 'short' => 'إنتاج مصاصات بابل بمقاسات خاصة.', 'desc' => 'ننتج مصاصات بابل بمقاسات خاصة مع عبوات 100 قطعة وخيارات مطبوعة أو غير مطبوعة.'],
                ],
            ],
            [
                'lookup' => ['paper-straws-contract-manufacturing'],
                'category' => 'pipet',
                'image' => 'images/catalog/asset-10.jpg',
                'min_order' => 50000,
                'print' => true,
                'wrap' => true,
                'lead' => 25,
                'specs' => [
                    'tr' => ['Model' => 'Kağıt pipet (fason)', 'Baskı' => 'Sargıya veya pipete baskı', 'Paketleme' => 'Sargılı/sargısız', 'Min. Sipariş' => '50.000 adet', 'Termin' => '25 iş günü'],
                    'en' => ['Model' => 'Paper straws (contract)', 'Printing' => 'Print on wrapper or straw', 'Packaging' => 'Wrapped/unwrapped', 'Min. Order' => '50,000 units', 'Lead Time' => '25 business days'],
                    'ru' => ['Модель' => 'Бумажные трубочки (контракт)', 'Печать' => 'На обертке или трубочке', 'Упаковка' => 'С оберткой/без', 'Мин. заказ' => '50 000 шт', 'Срок' => '25 рабочих дней'],
                    'ar' => ['الموديل' => 'مصاصات ورقية (تعاقدي)', 'الطباعة' => 'على الغلاف أو المصاصة', 'التغليف' => 'مغلف/غير مغلف', 'الحد الأدنى' => '50,000 قطعة', 'مدة التنفيذ' => '25 يوم عمل'],
                ],
                'translations' => [
                    'tr' => ['name' => 'Kağıt Pipet (Fason Üretim)', 'slug' => 'kagit-pipet-fason-uretim', 'short' => 'Fason modelde kağıt pipet üretimi.', 'desc' => 'Kağıt pipet üretimi fason modelde yapılır. Sargılı/sargısız ve sargıya/pipete baskı seçenekleri sunulur.'],
                    'en' => ['name' => 'Paper Straws (Contract Manufacturing)', 'slug' => 'paper-straws-contract-manufacturing', 'short' => 'Contract-based paper straw production.', 'desc' => 'Paper straws are supplied via contract manufacturing with wrapper/straw print and wrapped/unwrapped options.'],
                    'ru' => ['name' => 'Бумажные трубочки (контрактное производство)', 'slug' => 'paper-straws-contract-manufacturing', 'short' => 'Контрактное производство бумажных трубочек.', 'desc' => 'Бумажные трубочки поставляются по контрактной модели с разными вариантами печати и упаковки.'],
                    'ar' => ['name' => 'مصاصات ورقية (تصنيع تعاقدي)', 'slug' => 'paper-straws-contract-manufacturing', 'short' => 'تصنيع تعاقدي للمصاصات الورقية.', 'desc' => 'نوفر المصاصات الورقية بنموذج تصنيع تعاقدي مع خيارات طباعة وتغليف متعددة.'],
                ],
            ],
            [
                'lookup' => ['printed-paper-cups'],
                'category' => 'bardak',
                'image' => 'images/catalog/asset-11.jpg',
                'min_order' => 1000,
                'print' => true,
                'wrap' => false,
                'lead' => 25,
                'specs' => [
                    'tr' => ['Model' => 'Baskılı karton bardak', 'Kapak' => 'Kapaklı/kapaksız', 'Paketleme' => '50’li paket', 'Min. Sipariş' => '1.000 adet (1 koli)', 'Termin' => '25 iş günü'],
                    'en' => ['Model' => 'Printed paper cups', 'Lid' => 'With/without lid', 'Packaging' => 'Packs of 50', 'Min. Order' => '1,000 units', 'Lead Time' => '25 business days'],
                    'ru' => ['Модель' => 'Бумажные стаканы с печатью', 'Крышка' => 'С/без крышки', 'Упаковка' => 'По 50 шт', 'Мин. заказ' => '1 000 шт', 'Срок' => '25 рабочих дней'],
                    'ar' => ['الموديل' => 'أكواب ورقية مطبوعة', 'الغطاء' => 'بغطاء/بدون', 'التغليف' => 'عبوات 50', 'الحد الأدنى' => '1,000 قطعة', 'مدة التنفيذ' => '25 يوم عمل'],
                ],
                'translations' => [
                    'tr' => ['name' => 'Baskılı Karton Bardak', 'slug' => 'baskili-karton-bardak', 'short' => 'Baskılı karton bardak üretimi.', 'desc' => 'Baskılı karton bardak üretimi kapaklı/kapaksız seçeneklerle yapılır. Ürünler 50’li paketlenir.'],
                    'en' => ['name' => 'Printed Paper Cups', 'slug' => 'printed-paper-cups', 'short' => 'Printed paper cup production.', 'desc' => 'Printed paper cups are supplied with or without lids and packed in sets of 50.'],
                    'ru' => ['name' => 'Бумажные стаканы с печатью', 'slug' => 'printed-paper-cups', 'short' => 'Производство бумажных стаканов с печатью.', 'desc' => 'Печать на бумажных стаканах с вариантами крышки и упаковкой по 50 шт.'],
                    'ar' => ['name' => 'أكواب ورقية مطبوعة', 'slug' => 'printed-paper-cups', 'short' => 'إنتاج أكواب ورقية مطبوعة.', 'desc' => 'نوفر أكواب ورقية مطبوعة بخيارات بغطاء أو بدون غطاء وتغليف كل 50 قطعة.'],
                ],
            ],
            [
                'lookup' => ['plain-paper-cups'],
                'category' => 'bardak',
                'image' => 'images/catalog/asset-12.jpg',
                'min_order' => 1000,
                'print' => false,
                'wrap' => false,
                'lead' => 25,
                'specs' => [
                    'tr' => ['Model' => 'Baskısız karton bardak', 'Min. Sipariş' => '1.000 adet', 'Termin' => '25 iş günü'],
                    'en' => ['Model' => 'Plain paper cups', 'Min. Order' => '1,000 units', 'Lead Time' => '25 business days'],
                    'ru' => ['Модель' => 'Бумажные стаканы без печати', 'Мин. заказ' => '1 000 шт', 'Срок' => '25 рабочих дней'],
                    'ar' => ['الموديل' => 'أكواب ورقية بدون طباعة', 'الحد الأدنى' => '1,000 قطعة', 'مدة التنفيذ' => '25 يوم عمل'],
                ],
                'translations' => [
                    'tr' => ['name' => 'Baskısız Karton Bardak', 'slug' => 'baskisiz-karton-bardak', 'short' => 'Baskısız karton bardak tedariki.', 'desc' => 'Baskısız karton bardak ürününde minimum sipariş 1.000 adettir.'],
                    'en' => ['name' => 'Plain Paper Cups', 'slug' => 'plain-paper-cups', 'short' => 'Plain paper cup supply.', 'desc' => 'Plain paper cups are supplied with a minimum order of 1,000 units.'],
                    'ru' => ['name' => 'Бумажные стаканы без печати', 'slug' => 'plain-paper-cups', 'short' => 'Поставка бумажных стаканов без печати.', 'desc' => 'Бумажные стаканы без печати поставляются от 1 000 шт.'],
                    'ar' => ['name' => 'أكواب ورقية بدون طباعة', 'slug' => 'plain-paper-cups', 'short' => 'توريد أكواب ورقية بدون طباعة.', 'desc' => 'يبدأ الحد الأدنى لتوريد الأكواب الورقية بدون طباعة من 1,000 قطعة.'],
                ],
            ],
            [
                'lookup' => ['printed-pet-cups'],
                'category' => 'bardak',
                'image' => 'images/catalog/asset-14.jpg',
                'min_order' => 1000,
                'print' => true,
                'wrap' => false,
                'lead' => 15,
                'specs' => [
                    'tr' => ['Model' => 'Baskılı PET bardak', 'Min. Sipariş' => '1.000 adet', 'Termin' => '15 iş günü'],
                    'en' => ['Model' => 'Printed PET cups', 'Min. Order' => '1,000 units', 'Lead Time' => '15 business days'],
                    'ru' => ['Модель' => 'PET-стаканы с печатью', 'Мин. заказ' => '1 000 шт', 'Срок' => '15 рабочих дней'],
                    'ar' => ['الموديل' => 'أكواب PET مطبوعة', 'الحد الأدنى' => '1,000 قطعة', 'مدة التنفيذ' => '15 يوم عمل'],
                ],
                'translations' => [
                    'tr' => ['name' => 'Baskılı PET Bardak', 'slug' => 'baskili-pet-bardak', 'short' => 'Baskılı PET bardak üretimi.', 'desc' => 'Baskılı PET bardak ürününde minimum sipariş 1.000 adet ve termin 15 iş günüdür.'],
                    'en' => ['name' => 'Printed PET Cups', 'slug' => 'printed-pet-cups', 'short' => 'Printed PET cup production.', 'desc' => 'Printed PET cups are supplied with a 1,000 MOQ and 15-business-day lead time.'],
                    'ru' => ['name' => 'PET-стаканы с печатью', 'slug' => 'printed-pet-cups', 'short' => 'Производство PET-стаканов с печатью.', 'desc' => 'Минимальный заказ печатных PET-стаканов — 1 000 шт, срок 15 рабочих дней.'],
                    'ar' => ['name' => 'أكواب PET مطبوعة', 'slug' => 'printed-pet-cups', 'short' => 'إنتاج أكواب PET مطبوعة.', 'desc' => 'الحد الأدنى للأكواب المطبوعة من PET هو 1,000 قطعة ومدة التنفيذ 15 يوم عمل.'],
                ],
            ],
            [
                'lookup' => ['pet-cups'],
                'category' => 'bardak',
                'image' => 'images/catalog/asset-13.jpg',
                'min_order' => 1000,
                'print' => false,
                'wrap' => false,
                'lead' => 15,
                'specs' => [
                    'tr' => ['Model' => 'Baskısız PET bardak', 'Min. Sipariş' => '1.000 adet', 'Termin' => '15 iş günü'],
                    'en' => ['Model' => 'Plain PET cups', 'Min. Order' => '1,000 units', 'Lead Time' => '15 business days'],
                    'ru' => ['Модель' => 'PET-стаканы без печати', 'Мин. заказ' => '1 000 шт', 'Срок' => '15 рабочих дней'],
                    'ar' => ['الموديل' => 'أكواب PET بدون طباعة', 'الحد الأدنى' => '1,000 قطعة', 'مدة التنفيذ' => '15 يوم عمل'],
                ],
                'translations' => [
                    'tr' => ['name' => 'Baskısız PET Bardak', 'slug' => 'baskisiz-pet-bardak', 'short' => 'Baskısız PET bardak tedariki.', 'desc' => 'Baskısız PET bardak ürününde minimum sipariş 1.000 adettir.'],
                    'en' => ['name' => 'Plain PET Cups', 'slug' => 'plain-pet-cups', 'short' => 'Plain PET cup supply.', 'desc' => 'Plain PET cups are supplied with a minimum order of 1,000 units.'],
                    'ru' => ['name' => 'PET-стаканы без печати', 'slug' => 'plain-pet-cups', 'short' => 'Поставка PET-стаканов без печати.', 'desc' => 'PET-стаканы без печати поставляются от 1 000 шт.'],
                    'ar' => ['name' => 'أكواب PET بدون طباعة', 'slug' => 'plain-pet-cups', 'short' => 'توريد أكواب PET بدون طباعة.', 'desc' => 'يبدأ الحد الأدنى للأكواب PET بدون طباعة من 1,000 قطعة.'],
                ],
            ],
            [
                'lookup' => ['pet-cup-lids'],
                'category' => 'bardak',
                'image' => 'images/catalog/asset-31.jpg',
                'min_order' => 1000,
                'print' => false,
                'wrap' => false,
                'lead' => 15,
                'specs' => [
                    'tr' => ['Model' => 'PET bardak kapak', 'Tipler' => 'Düz/klipsli/bombe', 'Min. Sipariş' => '1.000 adet', 'Termin' => '15 iş günü'],
                    'en' => ['Model' => 'PET cup lids', 'Types' => 'Flat/clip/dome', 'Min. Order' => '1,000 units', 'Lead Time' => '15 business days'],
                    'ru' => ['Модель' => 'Крышки PET', 'Типы' => 'Плоская/клипса/купол', 'Мин. заказ' => '1 000 шт', 'Срок' => '15 рабочих дней'],
                    'ar' => ['الموديل' => 'أغطية PET', 'الأنواع' => 'مسطح/مشبك/قبة', 'الحد الأدنى' => '1,000 قطعة', 'مدة التنفيذ' => '15 يوم عمل'],
                ],
                'translations' => [
                    'tr' => ['name' => 'PET Bardak Kapak', 'slug' => 'pet-bardak-kapak', 'short' => 'Düz, klipsli ve bombe PET kapak seçenekleri.', 'desc' => 'PET bardak kapak ürününde düz, klipsli ve bombe tip seçenekleri sunulur.'],
                    'en' => ['name' => 'PET Cup Lids', 'slug' => 'pet-cup-lids', 'short' => 'Flat, clip and dome PET lid options.', 'desc' => 'PET cup lids are available as flat, clip and dome types.'],
                    'ru' => ['name' => 'Крышки для PET-стаканов', 'slug' => 'pet-cup-lids', 'short' => 'Плоские, клипсовые и купольные крышки PET.', 'desc' => 'Для PET-стаканов доступны крышки трех типов: плоские, клипсовые, купольные.'],
                    'ar' => ['name' => 'أغطية أكواب PET', 'slug' => 'pet-cup-lids', 'short' => 'خيارات أغطية PET مسطحة ومشبك وقبة.', 'desc' => 'نوفر أغطية أكواب PET بثلاثة أنواع: مسطح، مشبك، وقبة.'],
                ],
            ],
            [
                'lookup' => ['printed-napkins'],
                'category' => 'pecete',
                'image' => 'images/catalog/asset-15.jpg',
                'min_order' => 20,
                'print' => true,
                'wrap' => false,
                'lead' => 25,
                'specs' => [
                    'tr' => ['Model' => 'Peçete', 'Koli içi' => '2.400 veya 4.800', 'Min. Sipariş' => '20 koli', 'Termin' => '25 iş günü'],
                    'en' => ['Model' => 'Napkins', 'Carton qty' => '2,400 or 4,800', 'Min. Order' => '20 cartons', 'Lead Time' => '25 business days'],
                    'ru' => ['Модель' => 'Салфетки', 'В коробке' => '2 400 или 4 800', 'Мин. заказ' => '20 коробов', 'Срок' => '25 рабочих дней'],
                    'ar' => ['الموديل' => 'مناديل', 'كمية الكرتون' => '2,400 أو 4,800', 'الحد الأدنى' => '20 كرتون', 'مدة التنفيذ' => '25 يوم عمل'],
                ],
                'translations' => [
                    'tr' => ['name' => 'Peçete (Baskılı / Baskısız)', 'slug' => 'pecete-baskili-baskisiz', 'short' => 'Baskılı/baskısız peçete üretimi.', 'desc' => 'Peçete ürününde koli içi 2.400 veya 4.800 adet seçenekleri ile minimum 20 koli sipariş çalışılır.'],
                    'en' => ['name' => 'Napkins (Printed / Plain)', 'slug' => 'napkins-printed-plain', 'short' => 'Printed/plain napkin production.', 'desc' => 'Napkin production is available with carton options of 2,400 or 4,800 and a 20-carton MOQ.'],
                    'ru' => ['name' => 'Салфетки (с печатью / без)', 'slug' => 'napkins-printed-plain', 'short' => 'Производство салфеток с печатью и без.', 'desc' => 'Салфетки поставляются коробами по 2 400 или 4 800 шт, минимум 20 коробов.'],
                    'ar' => ['name' => 'مناديل (مطبوع / غير مطبوع)', 'slug' => 'napkins-printed-plain', 'short' => 'إنتاج مناديل مطبوعة وغير مطبوعة.', 'desc' => 'نوفر المناديل بعبوات 2,400 أو 4,800 وبحد أدنى 20 كرتون.'],
                ],
            ],
            [
                'lookup' => ['single-sachet-wet-wipes'],
                'category' => 'islak-mendil',
                'image' => 'images/catalog/asset-17.jpg',
                'min_order' => 10000,
                'print' => true,
                'wrap' => true,
                'lead' => 20,
                'specs' => [
                    'tr' => ['Model' => 'Tekli ıslak mendil', 'Baskı' => 'Baskılı / baskısız', 'Min. Sipariş' => '10.000 adet', '4+ Renk Baskı' => '20.000 adet', 'Termin' => '20 iş günü'],
                    'en' => ['Model' => 'Single wet wipes', 'Printing' => 'Printed/plain', 'Min. Order' => '10,000 units', '4+ Color Print' => '20,000 units', 'Lead Time' => '20 business days'],
                    'ru' => ['Модель' => 'Влажные салфетки в саше', 'Печать' => 'С печатью / без', 'Мин. заказ' => '10 000 шт', 'Печать 4+ цвета' => '20 000 шт', 'Срок' => '20 рабочих дней'],
                    'ar' => ['الموديل' => 'مناديل مبللة فردية', 'الطباعة' => 'مطبوع / غير مطبوع', 'الحد الأدنى' => '10,000 قطعة', 'طباعة 4 ألوان+' => '20,000 قطعة', 'مدة التنفيذ' => '20 يوم عمل'],
                ],
                'translations' => [
                    'tr' => ['name' => 'Tekli Islak Mendil', 'slug' => 'tekli-islak-mendil', 'short' => 'Tekli ıslak mendil üretimi.', 'desc' => 'Islak mendil ürününde tekli formatta baskılı veya baskısız üretim yapılır. 4 renk üzeri baskıda minimum adet 20.000’dir.'],
                    'en' => ['name' => 'Single Wet Wipes', 'slug' => 'single-wet-wipes', 'short' => 'Single sachet wet wipe production.', 'desc' => 'Wet wipes are produced only in single sachet format with printed or plain options. 4+ color print requires 20,000 MOQ.'],
                    'ru' => ['name' => 'Влажные салфетки в саше', 'slug' => 'single-wet-wipes', 'short' => 'Производство влажных салфеток в саше.', 'desc' => 'Влажные салфетки поставляются только в формате саше. При печати 4+ цветов минимальный заказ 20 000 шт.'],
                    'ar' => ['name' => 'مناديل مبللة فردية', 'slug' => 'single-wet-wipes', 'short' => 'إنتاج مناديل مبللة فردية.', 'desc' => 'نوفر المناديل المبللة بصيغة فردية فقط بخيار مطبوع أو غير مطبوع. طباعة 4 ألوان+ تتطلب حدًا أدنى 20,000 قطعة.'],
                ],
            ],
            [
                'lookup' => ['flag-toothpicks-standard'],
                'category' => 'bayrakli-kurdan',
                'image' => 'images/catalog/asset-20.jpg',
                'min_order' => 5000,
                'print' => true,
                'wrap' => false,
                'lead' => 20,
                'specs' => [
                    'tr' => ['Model' => 'Bayraklı kürdan (standart)', 'Baskı' => 'Tek/çift yüz', 'Min. Sipariş' => '5.000 adet', 'Termin' => '20 iş günü'],
                    'en' => ['Model' => 'Flag toothpicks (standard)', 'Printing' => 'Single/double side', 'Min. Order' => '5,000 units', 'Lead Time' => '20 business days'],
                    'ru' => ['Модель' => 'Флажковые зубочистки', 'Печать' => 'Одно/двусторонняя', 'Мин. заказ' => '5 000 шт', 'Срок' => '20 рабочих дней'],
                    'ar' => ['الموديل' => 'أعواد أسنان بالأعلام', 'الطباعة' => 'وجه واحد/وجهان', 'الحد الأدنى' => '5,000 قطعة', 'مدة التنفيذ' => '20 يوم عمل'],
                ],
                'translations' => [
                    'tr' => ['name' => 'Bayraklı Kürdan (Standart)', 'slug' => 'bayrakli-kurdan-standart', 'short' => 'Standart ölçü bayraklı kürdan.', 'desc' => 'Standart ölçü bayraklı kürdanda tek veya çift yüz baskı seçenekleri sunulur.'],
                    'en' => ['name' => 'Flag Toothpicks (Standard)', 'slug' => 'flag-toothpicks-standard', 'short' => 'Standard-size flag toothpicks.', 'desc' => 'Flag toothpicks are supplied in standard size with single or double side print options.'],
                    'ru' => ['name' => 'Флажковые зубочистки (стандарт)', 'slug' => 'flag-toothpicks-standard', 'short' => 'Стандартные флажковые зубочистки.', 'desc' => 'Стандартные флажковые зубочистки доступны с односторонней и двусторонней печатью.'],
                    'ar' => ['name' => 'أعواد أسنان بالأعلام (قياسي)', 'slug' => 'flag-toothpicks-standard', 'short' => 'أعواد أسنان بالأعلام بالمقاس القياسي.', 'desc' => 'أعواد الأسنان بالأعلام بالمقاس القياسي متاحة بطباعة بوجه واحد أو وجهين.'],
                ],
            ],
            [
                'lookup' => ['custom-size-flag-toothpicks'],
                'category' => 'bayrakli-kurdan',
                'image' => 'images/catalog/asset-21.jpg',
                'min_order' => 5000,
                'print' => true,
                'wrap' => false,
                'lead' => 20,
                'specs' => [
                    'tr' => ['Model' => 'Özel ölçü bayraklı kürdan', 'Baskı' => 'Tek/çift yüz', 'Min. Sipariş' => '5.000 adet', 'Termin' => '20 iş günü'],
                    'en' => ['Model' => 'Custom size flag toothpicks', 'Printing' => 'Single/double side', 'Min. Order' => '5,000 units', 'Lead Time' => '20 business days'],
                    'ru' => ['Модель' => 'Флажковые зубочистки (кастом)', 'Печать' => 'Одно/двусторонняя', 'Мин. заказ' => '5 000 шт', 'Срок' => '20 рабочих дней'],
                    'ar' => ['الموديل' => 'أعواد أسنان بالأعلام (مخصص)', 'الطباعة' => 'وجه واحد/وجهان', 'الحد الأدنى' => '5,000 قطعة', 'مدة التنفيذ' => '20 يوم عمل'],
                ],
                'translations' => [
                    'tr' => ['name' => 'Özel Ölçü Bayraklı Kürdan', 'slug' => 'ozel-olcu-bayrakli-kurdan', 'short' => 'Özel ölçü bayraklı kürdan.', 'desc' => 'Bayraklı kürdanda özel ölçü, tek/çift yüz baskı ve proje bazlı planlama uygulanır.'],
                    'en' => ['name' => 'Custom Size Flag Toothpicks', 'slug' => 'custom-size-flag-toothpicks', 'short' => 'Custom-size flag toothpicks.', 'desc' => 'Custom-size flag toothpicks are produced with single or double side print options.'],
                    'ru' => ['name' => 'Флажковые зубочистки (индивидуальный размер)', 'slug' => 'custom-size-flag-toothpicks', 'short' => 'Флажковые зубочистки по индивидуальному размеру.', 'desc' => 'Производим флажковые зубочистки по размеру проекта с одно- или двусторонней печатью.'],
                    'ar' => ['name' => 'أعواد أسنان بالأعلام (مقاس خاص)', 'slug' => 'custom-size-flag-toothpicks', 'short' => 'أعواد أسنان بالأعلام بمقاس خاص.', 'desc' => 'يتم إنتاج أعواد الأسنان بالأعلام بمقاس خاص مع خيارات طباعة بوجه واحد أو وجهين.'],
                ],
            ],
            [
                'lookup' => ['printed-stick-sugar'],
                'category' => 'stick-seker',
                'image' => 'images/catalog/asset-22.jpg',
                'min_order' => 20000,
                'print' => true,
                'wrap' => true,
                'lead' => 20,
                'specs' => [
                    'tr' => ['Model' => 'Stick şeker', 'Tür' => 'Beyaz/esmer', 'Baskı' => 'Baskılı / baskısız', 'Min. Sipariş' => '20.000 adet', 'Termin' => '20 iş günü'],
                    'en' => ['Model' => 'Stick sugar', 'Type' => 'White/brown', 'Printing' => 'Printed/plain', 'Min. Order' => '20,000 units', 'Lead Time' => '20 business days'],
                    'ru' => ['Модель' => 'Стик-сахар', 'Тип' => 'Белый/тростниковый', 'Печать' => 'С печатью/без', 'Мин. заказ' => '20 000 шт', 'Срок' => '20 рабочих дней'],
                    'ar' => ['الموديل' => 'سكر ساشيه', 'النوع' => 'أبيض/بني', 'الطباعة' => 'مطبوع/غير مطبوع', 'الحد الأدنى' => '20,000 قطعة', 'مدة التنفيذ' => '20 يوم عمل'],
                ],
                'translations' => [
                    'tr' => ['name' => 'Stick Şeker (Baskılı / Baskısız)', 'slug' => 'stick-seker', 'short' => 'Baskılı veya baskısız stick şeker.', 'desc' => 'Stick şeker ürününde beyaz veya esmer içerik seçeneğiyle baskılı/baskısız tedarik yapılır.'],
                    'en' => ['name' => 'Stick Sugar (Printed / Plain)', 'slug' => 'stick-sugar', 'short' => 'Printed or plain stick sugar.', 'desc' => 'Stick sugar is supplied in printed or plain packs with white or brown sugar options.'],
                    'ru' => ['name' => 'Стик-сахар (с печатью / без)', 'slug' => 'stick-sugar', 'short' => 'Стик-сахар с печатью или без.', 'desc' => 'Стик-сахар поставляется с печатью или без печати, с выбором белого или тростникового сахара.'],
                    'ar' => ['name' => 'سكر ساشيه (مطبوع / غير مطبوع)', 'slug' => 'stick-sugar', 'short' => 'سكر ساشيه مطبوع أو غير مطبوع.', 'desc' => 'نوفر السكر الساشيه بخيارات مطبوعة وغير مطبوعة مع نوع سكر أبيض أو بني.'],
                ],
            ],
            [
                'lookup' => ['sticker-printing-service'],
                'category' => 'sticker',
                'image' => 'images/catalog/asset-32.jpg',
                'min_order' => 1000,
                'print' => true,
                'wrap' => false,
                'lead' => 10,
                'specs' => [
                    'tr' => ['Model' => 'Sticker baskı hizmeti', 'Ölçü' => 'İstenilen ölçüler', 'Termin' => '10 iş günü'],
                    'en' => ['Model' => 'Sticker printing service', 'Size' => 'Custom sizes', 'Lead Time' => '10 business days'],
                    'ru' => ['Модель' => 'Печать стикеров', 'Размер' => 'По запросу', 'Срок' => '10 рабочих дней'],
                    'ar' => ['الموديل' => 'خدمة طباعة ستيكر', 'المقاس' => 'حسب الطلب', 'مدة التنفيذ' => '10 أيام عمل'],
                ],
                'translations' => [
                    'tr' => ['name' => 'Sticker Baskı Hizmeti', 'slug' => 'sticker-baski-hizmeti', 'short' => 'İstenilen ölçülerde sticker baskı.', 'desc' => 'Sticker baskı hizmetinde proje ölçülerine göre üretim yapılır ve standart termin 10 iş günüdür.'],
                    'en' => ['name' => 'Sticker Printing Service', 'slug' => 'sticker-printing-service', 'short' => 'Custom-size sticker printing service.', 'desc' => 'Sticker printing is provided in custom sizes with a standard 10-business-day lead time.'],
                    'ru' => ['name' => 'Услуга печати стикеров', 'slug' => 'sticker-printing-service', 'short' => 'Печать стикеров по индивидуальным размерам.', 'desc' => 'Печать стикеров выполняется по проектным размерам, стандартный срок 10 рабочих дней.'],
                    'ar' => ['name' => 'خدمة طباعة ستيكر', 'slug' => 'sticker-printing-service', 'short' => 'طباعة ستيكر بمقاسات خاصة.', 'desc' => 'نوفر خدمة طباعة ستيكر بمقاسات حسب الطلب وبمدة تنفيذ قياسية 10 أيام عمل.'],
                ],
            ],
        ];

        foreach ($items as $item) {
            $translationSlugs = collect($item['translations'])
                ->pluck('slug')
                ->filter()
                ->values()
                ->all();

            $lookupSlugs = array_values(array_unique(array_merge($item['lookup'], $translationSlugs)));

            $product = $this->findProductByAnySlug($lookupSlugs) ?? Product::query()->create([
                'category_id' => $categories[$item['category']]->id,
                'min_order' => $item['min_order'],
                'has_print' => $item['print'],
                'has_wrapping' => $item['wrap'],
                'is_active' => true,
                'image' => $item['image'],
            ]);

            // If any locale slug is already owned by another product, keep that product as the source of truth.
            $ownedProductId = ProductTranslation::query()
                ->whereIn('slug', $lookupSlugs)
                ->value('product_id');
            if ($ownedProductId && $ownedProductId !== $product->id) {
                $product = Product::query()->find($ownedProductId) ?? $product;
            }

            $product->update([
                'category_id' => $categories[$item['category']]->id,
                'min_order' => $item['min_order'],
                'has_print' => $item['print'],
                'has_wrapping' => $item['wrap'],
                'is_active' => true,
                'image' => $item['image'],
                'specs' => ['lead_time_days' => $item['lead'], 'tr' => $item['specs']['tr'], 'en' => $item['specs']['en'], 'ru' => $item['specs']['ru'], 'ar' => $item['specs']['ar']],
            ]);

            foreach (self::LOCALES as $locale) {
                $t = $item['translations'][$locale];
                $product->translations()->updateOrCreate(
                    ['lang' => $locale],
                    [
                        'name' => $t['name'],
                        'slug' => $t['slug'],
                        'short_desc' => $t['short'],
                        'description' => $t['desc'],
                        'seo_title' => mb_substr($t['name'] . ' | Lunar Ambalaj', 0, 60),
                        'seo_desc' => mb_substr($t['short'], 0, 160),
                    ],
                );
            }
        }
    }

    private function deactivateDeprecatedVariants(): void
    {
        Product::query()->whereHas('translations', function ($query): void {
            $query->where('lang', 'en')->whereIn('slug', ['plain-napkins', 'private-label-wet-wipes', 'restaurant-wet-wipes', 'plain-stick-sugar', 'logo-printed-stick-sugar']);
        })->update(['is_active' => false]);
    }

    private function updateBlogMeasurements(): void
    {
        $post = Post::query()->whereHas('translations', function ($query): void {
            $query->where('lang', 'tr')->where(function ($sub): void {
                $sub->where('slug', 'cok-amacli-pipetlerde-olcu-ve-kullanim-rehberi')->orWhere('title', 'like', '%ölçü ve kullanım rehberi%');
            });
        })->with('translations')->first();

        if (! $post) {
            return;
        }

        $texts = [
            'tr' => ['title' => 'Körüklü pipetlerde ölçü ve kullanım rehberi', 'body' => 'Körüklü pipet için standart ölçü 8x24 cm olmalıdır. Bu ölçü, soğuk içecek servisinde kullanım konforu ve operasyon standardı sağlar.'],
            'en' => ['title' => 'Size and usage guide for corrugated straws', 'body' => 'The standard size for corrugated straws should be 8x24 cm. This format provides balanced usability in cold beverage service.'],
            'ru' => ['title' => 'Руководство по размеру для гофрированных трубочек', 'body' => 'Для гофрированных трубочек стандартный размер должен быть 8x24 см. Этот формат удобен для подачи холодных напитков.'],
            'ar' => ['title' => 'دليل مقاس المصاصات المرنة', 'body' => 'المقاس القياسي للمصاصات المرنة يجب أن يكون 8x24 سم، وهو مناسب لخدمة المشروبات الباردة.'],
        ];

        foreach ($post->translations as $translation) {
            if (! isset($texts[$translation->lang])) {
                continue;
            }

            $translation->update([
                'title' => $texts[$translation->lang]['title'],
                'body' => $texts[$translation->lang]['body'],
                'seo_title' => mb_substr($texts[$translation->lang]['title'] . ' | Lunar Ambalaj Blog', 0, 60),
                'seo_desc' => mb_substr($texts[$translation->lang]['body'], 0, 160),
            ]);
        }
    }

    private function updateHeroSettings(): void
    {
        Setting::query()->updateOrCreate(['id' => 1], [
            'hero_h1_tr' => 'Plastik Frozen ve Özel Ölçü Pipet Üretiminde Tek Üretici',
            'hero_h1_en' => 'One Manufacturer for Plastic Frozen and Custom Straw Production',
            'hero_subtitle_tr' => 'Frozen, körüklü ve bubble pipet üretiminde baskılı/baskısız çözümler. Kağıt pipet üretimi fason modelde sunulur.',
            'hero_subtitle_en' => 'Printed and plain solutions for frozen, corrugated and bubble straws. Paper straws are supplied in contract manufacturing mode.',
        ]);
    }

    /**
     * @param list<string> $slugs
     */
    private function findProductByAnySlug(array $slugs): ?Product
    {
        return Product::query()->whereHas('translations', function ($query) use ($slugs): void {
            $query->whereIn('slug', $slugs);
        })->first();
    }
}
