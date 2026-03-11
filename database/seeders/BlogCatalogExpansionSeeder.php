<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class BlogCatalogExpansionSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->posts() as $i => $item) {
            $lookupSlugs = [
                $item['tr']['slug'],
                $item['en']['slug'],
                $item['ru']['slug'],
                $item['ar']['slug'],
                $item['es']['slug'],
            ];

            $post = Post::query()
                ->whereHas('translations', function ($query) use ($lookupSlugs): void {
                    $query->whereIn('slug', $lookupSlugs);
                })
                ->first();

            if (! $post) {
                $post = Post::query()->create([
                    'cover' => $item['cover'],
                    'published_at' => now()->subDays($i + 1),
                    'is_active' => true,
                ]);
            } else {
                $post->update([
                    'cover' => $item['cover'],
                    'is_active' => true,
                    'published_at' => $post->published_at ?: now()->subDays($i + 1),
                ]);
            }

            $this->upsertTranslation($post, 'tr', $item['tr'], 'Lunar Ambalaj Blog');
            $this->upsertTranslation($post, 'en', $item['en'], 'Lunar Packaging Blog');
            $this->upsertTranslation($post, 'ru', $item['ru'], 'Блог Lunar Packaging');
            $this->upsertTranslation($post, 'ar', $item['ar'], 'مدونة Lunar Packaging');
            $this->upsertTranslation($post, 'es', $item['es'], 'Blog Lunar Ambalaj');
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function posts(): array
    {
        return [
            [
                'cover' => 'images/catalog/asset-25.jpg',
                'tr' => $this->t('Plastik frozen pipet seçerken kritik kriterler', 'plastik-frozen-pipet-secerken-kritik-kriterler', 'Çap, malzeme, baskı ve termin için pratik kontrol listesi.', 'Plastik frozen pipet seçiminde içecek yoğunluğuna göre çap belirlemek, gıda temasına uygun hammadde kullanmak ve baskı provasını onaylamak kritik adımdır. MOQ, termin ve paketleme yapısı teklifte netleştirilmelidir.'),
                'en' => $this->t('Critical criteria when selecting plastic frozen straws', 'critical-criteria-when-selecting-plastic-frozen-straws', 'A practical checklist for diameter, material, print and lead time.', 'Selecting the right diameter for beverage density, confirming food-contact raw material and approving print proof are the key steps. MOQ, lead time and packaging structure should be finalized in quotation.'),
                'ru' => $this->t('Ключевые критерии выбора пластиковых frozen-трубочек', 'critical-criteria-when-selecting-plastic-frozen-straws', 'Практический чек-лист по диаметру, материалу, печати и сроку.', 'Ключевые шаги: подобрать диаметр под напиток, использовать пищевое сырье и согласовать цветопробу. MOQ, срок и формат упаковки лучше фиксировать в коммерческом предложении.'),
                'ar' => $this->t('المعايير الأساسية لاختيار مصاصات البلاستيك للمشروبات المجمدة', 'critical-criteria-when-selecting-plastic-frozen-straws', 'قائمة عملية للقطر والخامة والطباعة ومدة التنفيذ.', 'الخطوات الأساسية هي اختيار القطر المناسب للمشروب، استخدام خامة مناسبة للغذاء، واعتماد بروفة الطباعة. كما يجب تثبيت الحد الأدنى والمدة ونوع التعبئة داخل عرض السعر.'),
                'es' => $this->t('Criterios clave para elegir pajitas plasticas frozen', 'critical-criteria-when-selecting-plastic-frozen-straws', 'Checklist practico para diametro, material, impresion y plazo.', 'Los pasos clave son elegir el diametro correcto segun bebida, usar materia prima apta para contacto alimentario y aprobar prueba de impresion. MOQ, plazo y empaque deben quedar definidos en la cotizacion.'),
            ],
            [
                'cover' => 'images/catalog/asset-26.jpg',
                'tr' => $this->t('Körüklü pipetlerde ölçü ve kullanım rehberi', 'koruklu-pipetlerde-olcu-ve-kullanim-rehberi', '8x24 cm ölçüsü, kullanım alanı ve paketleme kararları için rehber.', 'Körüklü pipette sahada en dengeli ölçü 8x24 cm olarak öne çıkar. Baskılı veya baskısız seçenekler ile jelatinli-jelatinsiz paketleme, kanal ihtiyaçlarına göre birlikte planlanmalıdır.'),
                'en' => $this->t('Size and usage guide for corrugated straws', 'size-and-usage-guide-for-multifunction-straws', 'Guide for 8x24 cm sizing, usage and packaging decisions.', 'For corrugated straws, 8x24 cm is the most balanced operational size. Printed or plain format and wrapped or unwrapped packaging should be planned together according to channel needs.'),
                'ru' => $this->t('Руководство по размерам и применению гофрированных трубочек', 'size-and-usage-guide-for-multifunction-straws', 'Гид по размеру 8x24 см, применению и упаковке.', 'Для гофрированных трубочек размер 8x24 см считается наиболее сбалансированным. Формат с печатью/без печати и тип упаковки нужно планировать с учетом канала продаж.'),
                'ar' => $this->t('دليل المقاس والاستخدام للمصاصات المرنة', 'size-and-usage-guide-for-multifunction-straws', 'دليل لمقاس 8x24 سم والاستخدام وخيارات التعبئة.', 'في المصاصات المرنة يُعد مقاس 8x24 سم الأكثر توازناً. يجب تحديد الطباعة ونوع التغليف معاً حسب طبيعة القناة ومتطلبات التشغيل.'),
                'es' => $this->t('Guia de medidas y uso para pajitas corrugadas', 'size-and-usage-guide-for-multifunction-straws', 'Guia de medida 8x24 cm, uso y empaque.', 'En pajitas corrugadas, 8x24 cm es la medida mas equilibrada en operacion. Version impresa o lisa y formato envuelto o sin envolver deben planificarse segun el canal.'),
            ],
            [
                'cover' => 'images/catalog/asset-27.jpg',
                'tr' => $this->t('B2B ambalaj tedarikinde MOQ yönetimi', 'b2b-ambalaj-tedarikinde-moq-yonetimi', 'MOQ, maliyet, stok ve termin dengesini kurmak için yöntemler.', 'MOQ sadece minimum adet değildir; birim maliyet ve stok devir hızını da etkiler. Kategori bazlı planlama ve sevkiyat takviminin birlikte yönetilmesi, tedarik riskini düşürür.'),
                'en' => $this->t('MOQ management in B2B packaging supply', 'moq-management-in-b2b-packaging-supply', 'Methods to balance MOQ, cost, stock and lead time.', 'MOQ is not only a minimum quantity; it also impacts unit cost and stock turnover. Category-based planning and shipment scheduling together reduce supply risk.'),
                'ru' => $this->t('Управление MOQ в B2B-поставках упаковки', 'moq-management-in-b2b-packaging-supply', 'Как сбалансировать MOQ, себестоимость, запас и срок.', 'MOQ влияет не только на минимальный объем, но и на себестоимость и оборачиваемость. Планирование по категориям и согласованный график отгрузки снижают риски поставки.'),
                'ar' => $this->t('إدارة الحد الأدنى للطلب في توريد التعبئة B2B', 'moq-management-in-b2b-packaging-supply', 'موازنة MOQ مع الكلفة والمخزون والمدة.', 'الحد الأدنى للطلب يؤثر على تكلفة الوحدة ودوران المخزون. التخطيط حسب الفئات مع جدول شحن واضح يقلل مخاطر التوريد ويحسن الاستمرارية.'),
                'es' => $this->t('Gestion de MOQ en suministro B2B de empaques', 'moq-management-in-b2b-packaging-supply', 'Como equilibrar MOQ, costo, stock y plazo.', 'El MOQ impacta costo unitario y rotacion de inventario. Planificar por categoria y coordinar calendario de despacho reduce riesgo operativo.'),
            ],
            [
                'cover' => 'images/catalog/asset-28.jpg',
                'tr' => $this->t('Baskılı karton bardakta ölçü ve kapak seçimi', 'baskili-karton-bardakta-olcu-ve-kapak-secimi', 'Oz ölçüsü, kapak tipi ve paketleme kararları için yol haritası.', 'Baskılı karton bardakta ölçü seçimi menüye göre yapılmalıdır. Kapaklı-kapaksız maliyet farkı ve 50’li paketleme standardı birlikte değerlendirilirse operasyon daha stabil ilerler.'),
                'en' => $this->t('Size and lid selection for printed paper cups', 'printed-paper-cups-size-and-lid-selection', 'Roadmap for oz sizing, lid type and packaging decisions.', 'Printed paper cup size should follow menu structure. Evaluating lidded/non-lidded cost difference and pack-of-50 distribution together improves operational stability.'),
                'ru' => $this->t('Выбор объема и крышки для печатных бумажных стаканов', 'printed-paper-cups-size-and-lid-selection', 'План выбора объема, крышки и упаковки.', 'Выбор объема должен соответствовать меню. Совместная оценка стоимости вариантов с крышкой/без крышки и упаковки по 50 шт. делает процесс снабжения стабильнее.'),
                'ar' => $this->t('اختيار المقاس والغطاء في الأكواب الورقية المطبوعة', 'printed-paper-cups-size-and-lid-selection', 'خارطة قرار لحجم الكوب ونوع الغطاء والتعبئة.', 'يجب تحديد مقاس الكوب حسب قائمة المشروبات. كما أن دراسة فرق الكلفة بين النسخ مع الغطاء وبدونه، مع اعتماد تعبئة 50 قطعة، تعزز استقرار التشغيل.'),
                'es' => $this->t('Medida y seleccion de tapa en vasos de carton impresos', 'printed-paper-cups-size-and-lid-selection', 'Guia para definir oz, tapa y empaque.', 'La medida del vaso debe alinearse al menu. Evaluar costo con tapa/sin tapa junto con empaque de 50 unidades mejora la estabilidad operativa.'),
            ],
            [
                'cover' => 'images/catalog/asset-29.jpg',
                'tr' => $this->t('PET bardak ve kapak kombinasyon rehberi', 'pet-bardak-ve-kapak-kombinasyon-rehberi', 'Düz, klipsli ve bombe kapak seçimini kolaylaştıran rehber.', 'PET bardakta kapak seçimi servis hızını doğrudan etkiler. Düz, klipsli ve bombe kapak tipleri; sızdırmazlık, pipet uyumu ve taşıma senaryosu birlikte düşünülerek belirlenmelidir.'),
                'en' => $this->t('PET cup and lid combination guide', 'pet-cups-and-lid-combination-guide', 'Guide to selecting flat, clip and dome lids.', 'Lid choice directly affects PET cup service speed. Flat, clip and dome lid options should be decided with leakage control, straw fit and carrying scenario together.'),
                'ru' => $this->t('Руководство по сочетанию PET-стаканов и крышек', 'pet-cups-and-lid-combination-guide', 'Как выбрать плоскую, клипс- и dome-крышку.', 'Выбор крышки напрямую влияет на скорость сервиса. Тип крышки стоит определять с учетом герметичности, совместимости с трубочкой и формата потребления.'),
                'ar' => $this->t('دليل اختيار تركيبة أكواب PET والأغطية', 'pet-cups-and-lid-combination-guide', 'اختيار الغطاء المسطح أو المشبك أو القبة.', 'نوع الغطاء يؤثر مباشرة على سرعة الخدمة في أكواب PET. يجب تحديد الغطاء وفق منع التسرب وتوافق المصاصة وسيناريو الاستخدام.'),
                'es' => $this->t('Guia de combinacion de vaso PET y tapa', 'pet-cups-and-lid-combination-guide', 'Seleccion de tapa plana, clip y domo segun uso.', 'La seleccion de tapa influye en velocidad de servicio en vasos PET. Debe definirse segun control de derrame, compatibilidad con pajita y escenario de consumo.'),
            ],
            [
                'cover' => 'images/catalog/asset-30.jpg',
                'tr' => $this->t('Tekli ıslak mendilde baskı ve minimum sipariş planı', 'tekli-islak-mendilde-baski-ve-minimum-siparis-plani', 'Tekli ıslak mendilde baskı seviyesi, adet kırılımı ve termin yönetimi.', 'Tekli ıslak mendilde baskılı veya baskısız kararını kanal ihtiyacı belirler. MOQ ve renk sayısı birlikte planlandığında maliyet ve termin dengesi daha sağlıklı kurulur.'),
                'en' => $this->t('Print and MOQ planning for single wet wipes', 'single-wet-wipes-print-and-moq-planning', 'How to plan print level, minimum quantity and lead time.', 'Channel needs should drive printed or plain single wet wipe decisions. Planning MOQ with color scope together improves both cost and lead-time balance.'),
                'ru' => $this->t('Печать и планирование MOQ для индивидуальных влажных салфеток', 'single-wet-wipes-print-and-moq-planning', 'План по печати, минимальному объему и сроку.', 'Решение о печати зависит от канала продаж. Совместное планирование MOQ и количества цветов помогает сбалансировать стоимость и срок производства.'),
                'ar' => $this->t('تخطيط الطباعة والحد الأدنى للمناديل المبللة الفردية', 'single-wet-wipes-print-and-moq-planning', 'تحديد مستوى الطباعة والحد الأدنى والمدة.', 'اختيار النسخة المطبوعة أو غير المطبوعة يعتمد على القناة المستهدفة. التخطيط المشترك للحد الأدنى وعدد الألوان يحسن توازن الكلفة والمدة.'),
                'es' => $this->t('Plan de impresion y MOQ en toallitas humedas unitarias', 'single-wet-wipes-print-and-moq-planning', 'Plan de impresion, minimo y plazo para formato unitario.', 'La decision impresa o lisa depende del canal. Planificar MOQ junto con cantidad de colores mejora el equilibrio entre costo y plazo de produccion.'),
            ],
            [
                'cover' => 'images/catalog/asset-31.jpg',
                'tr' => $this->t('Stick şekerde baskılı/baskısız tedarik planı', 'stick-sekerde-baskili-baskisiz-tedarik-plani', 'Beyaz/esmer seçenekli stick şeker tedarikinde doğru planlama adımları.', 'Stick şekerde baskılı model marka görünürlüğünü artırırken baskısız model maliyet avantajı sağlayabilir. Şeker tipi, MOQ ve termin birlikte planlandığında satın alma süreci hızlanır.'),
                'en' => $this->t('Printed/plain stick sugar procurement plan', 'stick-sugar-procurement-plan-for-brands', 'Planning steps for branded or plain stick sugar supply.', 'Printed stick sugar can improve brand visibility while plain format can optimize cost. Defining sugar type, MOQ and lead time together speeds up procurement decisions.'),
                'ru' => $this->t('План поставок stick-сахара: с печатью или без', 'stick-sugar-procurement-plan-for-brands', 'Шаги планирования для брендированного и простого формата.', 'Печатный формат повышает видимость бренда, а непечатный может быть выгоднее по цене. Совместное согласование типа сахара, MOQ и сроков ускоряет закупку.'),
                'ar' => $this->t('خطة توريد السكر الساشيه: مطبوع أو غير مطبوع', 'stick-sugar-procurement-plan-for-brands', 'خطوات تخطيط التوريد للنسخة المطبوعة وغير المطبوعة.', 'النسخة المطبوعة تعزز حضور العلامة، بينما النسخة غير المطبوعة قد تقلل الكلفة. تحديد نوع السكر والحد الأدنى والمدة معاً يسرّع قرار الشراء.'),
                'es' => $this->t('Plan de suministro de azucar en stick: impresa o lisa', 'stick-sugar-procurement-plan-for-brands', 'Pasos para planificar suministro de formato impreso o liso.', 'El formato impreso mejora visibilidad de marca y el formato liso puede optimizar costo. Definir tipo de azucar, MOQ y plazo en conjunto acelera la compra.'),
            ],
            [
                'cover' => 'images/catalog/asset-32.jpg',
                'tr' => $this->t('Sticker baskı hizmetinde ölçü ve termin planlama', 'sticker-baski-hizmetinde-olcu-ve-termin-planlama', 'Sticker baskıda ölçü, yüzey ve 10 iş günü termin planı.', 'Sticker baskıda ölçü belirleme uygulama yüzeyiyle birlikte ele alınmalıdır. Dosya hazırlığında kesim payı ve renk profili doğru tanımlandığında 10 iş günü termin hedefi daha güvenli yönetilir.'),
                'en' => $this->t('Size and lead-time planning for sticker printing', 'sticker-printing-size-and-lead-time-planning', 'How to define size, surface and 10-business-day target.', 'Sticker dimensions should be defined together with application surface. Proper bleed and color profile setup in artwork helps manage a reliable 10-business-day lead time.'),
                'ru' => $this->t('Планирование размера и сроков в печати стикеров', 'sticker-printing-size-and-lead-time-planning', 'Как определить размер, поверхность и срок 10 рабочих дней.', 'Размер стикера должен определяться с учетом поверхности нанесения. Корректные вылеты и цветовой профиль в макете помогают стабильно выдерживать срок 10 рабочих дней.'),
                'ar' => $this->t('تخطيط المقاس والمدة في خدمة طباعة الستيكر', 'sticker-printing-size-and-lead-time-planning', 'تحديد المقاس والسطح ومدة 10 أيام عمل.', 'يجب تحديد المقاس مع نوع السطح المستهدف. ضبط هامش القص وبروفايل اللون في الملف يساعد على إدارة مدة تنفيذ مستقرة تبلغ 10 أيام عمل.'),
                'es' => $this->t('Planificacion de medida y plazo en impresion de stickers', 'sticker-printing-size-and-lead-time-planning', 'Definicion de medida, superficie y plazo de 10 dias habiles.', 'La medida del sticker debe definirse junto con la superficie de aplicacion. Configurar bien sangrado y perfil de color permite cumplir de forma estable el plazo de 10 dias habiles.'),
            ],
        ];
    }

    /**
     * @return array{title:string,slug:string,short_desc:string,body:string}
     */
    private function t(string $title, string $slug, string $shortDesc, string $body): array
    {
        return [
            'title' => $title,
            'slug' => $slug,
            'short_desc' => $shortDesc,
            'body' => $body,
        ];
    }

    /**
     * @param array{title:string,slug:string,short_desc:string,body:string} $data
     */
    private function upsertTranslation(Post $post, string $lang, array $data, string $seoBrand): void
    {
        $post->translations()->updateOrCreate(
            ['lang' => $lang],
            [
                'title' => $data['title'],
                'slug' => $data['slug'],
                'short_desc' => $data['short_desc'],
                'body' => $data['body'],
                'seo_title' => mb_substr($data['title'] . ' | ' . $seoBrand, 0, 60),
                'seo_desc' => mb_substr($data['short_desc'], 0, 160),
            ],
        );
    }
}
