<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Language;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ServiceItem;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class SpanishContentSeeder extends Seeder
{
    public function run(): void
    {
        Language::query()->updateOrCreate(
            ['code' => 'es'],
            ['name' => 'Espanol', 'is_default' => false],
        );

        $this->upsertPageTranslations();
        $this->upsertServiceTranslations();
        $this->upsertCategoryTranslations();
        $this->upsertProductTranslationsAndSpecs();
        $this->upsertPostTranslations();
        $this->upsertFaqTranslations();
        $this->upsertTestimonialTranslations();
    }

    private function upsertPageTranslations(): void
    {
        $pages = [
            'about' => [
                'title' => 'Nosotros',
                'slug' => 'about',
                'seo_title' => 'Nosotros | Lunar Ambalaj',
                'seo_desc' => 'Fabricante B2B de pajitas plasticas frozen, vasos, servilletas y productos de servicio para horeca.',
                'body' => '<p>Lunar Ambalaj - Tulgahan Yılkın es un fabricante enfocado en soluciones de consumo para horeca y compras corporativas. Nuestro núcleo de producción son las pajitas plásticas frozen, pajitas corrugadas y pajitas bubble a medida.</p><h2>Enfoque de producción</h2><ul><li>Pajitas plásticas impresas o sin impresión</li><li>Opciones de empaque individual o a granel</li><li>Planificación de lote, control de calidad y plazos definidos</li></ul><h2>Modelo de proveedor único</h2><p>Además de pajitas, integramos vasos, servilletas, toallitas húmedas, palillos con bandera, azúcar en stick y servicio de impresión de stickers. Así reducimos tiempos de compra y mejoramos la continuidad operativa.</p><h2>Mercado y operación</h2><p>Operamos desde Estambul con envíos en toda Turquía y soporte para proyectos de exportación. En productos personalizados avanzamos con validación de diseño, planificación de producción y calendario de entrega.</p>',
            ],
            'kvkk' => [
                'title' => 'Aviso de Privacidad (KVKK)',
                'slug' => 'kvkk',
                'seo_title' => 'Aviso de Privacidad (KVKK) | Lunar Ambalaj',
                'seo_desc' => 'Categorias de datos, finalidades, transferencias, conservacion y derechos del titular de datos.',
                'body' => $this->kvkkBody(),
            ],
            'privacy' => [
                'title' => 'Politica de Privacidad',
                'slug' => 'privacy-policy',
                'seo_title' => 'Politica de Privacidad | Lunar Ambalaj',
                'seo_desc' => 'Politica de privacidad para el uso del sitio, formularios y procesos de comunicacion comercial.',
                'body' => $this->privacyBody(),
            ],
            'cookie' => [
                'title' => 'Politica de Cookies',
                'slug' => 'cookie-policy',
                'seo_title' => 'Politica de Cookies | Lunar Ambalaj',
                'seo_desc' => 'Tipos de cookies, finalidades, terceros y gestion de cookies desde el navegador.',
                'body' => $this->cookieBody(),
            ],
            'distance_sales' => [
                'title' => 'Contrato de Venta a Distancia',
                'slug' => 'distance-sales-contract',
                'seo_title' => 'Contrato de Venta a Distancia | Lunar Ambalaj',
                'seo_desc' => 'Texto informativo sobre solicitudes de oferta, confirmacion comercial y condiciones de entrega.',
                'body' => $this->distanceSalesBody(),
            ],
            'terms' => [
                'title' => 'Terminos de Uso',
                'slug' => 'terms-of-use',
                'seo_title' => 'Terminos de Uso | Lunar Ambalaj',
                'seo_desc' => 'Condiciones de uso del sitio, propiedad intelectual, limitacion de responsabilidad y contacto.',
                'body' => $this->termsBody(),
            ],
        ];

        foreach ($pages as $key => $content) {
            $page = Page::query()
                ->where('key', $key)
                ->orWhere('type', $key)
                ->first();

            if (!$page) {
                continue;
            }

            $page->translations()->updateOrCreate(
                ['lang' => 'es'],
                [
                    'title' => $content['title'],
                    'slug' => $content['slug'],
                    'body' => $content['body'],
                    'seo_title' => mb_substr($content['seo_title'], 0, 60),
                    'seo_desc' => mb_substr($content['seo_desc'], 0, 160),
                ],
            );
        }
    }

    private function upsertServiceTranslations(): void
    {
        $map = [
            1 => [
                'title' => 'Fabricacion de Pajitas Plasticas',
                'body' => 'Produccion en serie de pajitas frozen, corrugadas y bubble con medidas y colores adaptados al proyecto.',
            ],
            2 => [
                'title' => 'Impresion Personalizada',
                'body' => 'Aplicamos logotipo y colores de marca en pajitas, vasos, servilletas y empaques tipo stick.',
            ],
            3 => [
                'title' => 'Empaque Individual',
                'body' => 'Opciones con y sin envoltorio para estandares de higiene en servicio horeca.',
            ],
            4 => [
                'title' => 'Produccion de Vasos',
                'body' => 'Fabricacion de vasos de carton y PET con configuracion impresa o lisa segun su operacion.',
            ],
            5 => [
                'title' => 'Toallitas Humedas Unitarias',
                'body' => 'Produccion de toallitas humedas en formato unitario para restaurante, hoteleria y cadenas.',
            ],
            6 => [
                'title' => 'Azucar en Stick y Presentacion',
                'body' => 'Suministro de azucar en stick impresa o lisa para mejorar la presencia de marca en mesa.',
            ],
        ];

        $services = ServiceItem::query()->with('translations')->get();
        foreach ($services as $service) {
            $seed = $map[$service->order] ?? null;
            if (!$seed) {
                $en = $service->translations->firstWhere('lang', 'en');
                if (!$en) {
                    continue;
                }

                $seed = [
                    'title' => $en->title,
                    'body' => $en->body,
                ];
            }

            $service->translations()->updateOrCreate(
                ['lang' => 'es'],
                [
                    'title' => $seed['title'],
                    'body' => $seed['body'],
                ],
            );
        }
    }

    private function upsertCategoryTranslations(): void
    {
        $map = [
            'straws' => ['name' => 'Pajitas', 'slug' => 'pajitas'],
            'cups' => ['name' => 'Vasos', 'slug' => 'vasos'],
            'napkins' => ['name' => 'Servilletas', 'slug' => 'servilletas'],
            'wet-wipes' => ['name' => 'Toallitas Humedas', 'slug' => 'toallitas-humedas'],
            'flag-toothpicks' => ['name' => 'Palillos con Bandera', 'slug' => 'palillos-bandera'],
            'stick-sugar' => ['name' => 'Azucar en Stick', 'slug' => 'azucar-stick'],
            'sticker-labels' => ['name' => 'Stickers y Etiquetas', 'slug' => 'stickers-etiquetas'],
        ];

        $categories = ProductCategory::query()->with('translations')->get();
        foreach ($categories as $category) {
            $en = $category->translations->firstWhere('lang', 'en');
            if (!$en) {
                continue;
            }

            $seed = $map[$en->slug] ?? [
                'name' => $en->name,
                'slug' => $en->slug,
            ];

            $category->translations()->updateOrCreate(
                ['lang' => 'es'],
                $seed,
            );
        }
    }

    private function upsertProductTranslationsAndSpecs(): void
    {
        $map = [
            'plastic-frozen-straws' => [
                'name' => 'Pajitas Plasticas Frozen',
                'slug' => 'pajitas-plasticas-frozen',
                'short_desc' => 'Produccion de pajitas frozen impresas o lisas para bebidas frias.',
                'description' => 'Fabricamos pajitas plasticas frozen con diametros 6-12 mm. Puede elegir impresion o version lisa, y empaque con o sin envoltorio en paquetes de 250 unidades. El plazo objetivo es de 20 dias habiles.',
            ],
            'corrugated-straws' => [
                'name' => 'Pajitas Corrugadas',
                'slug' => 'pajitas-corrugadas',
                'short_desc' => 'Pajitas corrugadas impresas o sin impresion para servicio horeca.',
                'description' => 'Las pajitas corrugadas se producen con opcion impresa o lisa. Se entregan con o sin envoltorio, normalmente en paquetes de 250 unidades. Adecuadas para cadenas de cafe y restaurantes.',
            ],
            'bubble-straws' => [
                'name' => 'Pajitas Bubble a Medida',
                'slug' => 'pajitas-bubble-medida',
                'short_desc' => 'Produccion de pajitas bubble con medidas especiales.',
                'description' => 'Para proyectos bubble tea fabricamos pajitas con diametro y largo definidos por cliente. Se ofrece impresion o version lisa, con empaque en paquetes de 100 unidades y plazo de 20 dias habiles.',
            ],
            'paper-straws-contract-manufacturing' => [
                'name' => 'Pajitas de Papel (Produccion por Maquila)',
                'slug' => 'pajitas-papel-maquila',
                'short_desc' => 'Suministro de pajitas de papel mediante modelo de maquila bajo pedido.',
                'description' => 'Las pajitas de papel se ofrecen en modelo de produccion por maquila. Se puede imprimir sobre envoltorio o sobre la pajita, con opciones envuelta o sin envolver. Pedido minimo 50.000 unidades.',
            ],
            'printed-paper-cups' => [
                'name' => 'Vasos de Carton Impresos',
                'slug' => 'vasos-carton-impresos',
                'short_desc' => 'Fabricacion de vasos de carton impresos para cadenas y eventos.',
                'description' => 'Fabricamos vasos de carton impresos con opcion de tapa o sin tapa. El empaque habitual es de 50 unidades por paquete y el minimo de pedido es 1.000 unidades.',
            ],
            'plain-paper-cups' => [
                'name' => 'Vasos de Carton Lisos',
                'slug' => 'vasos-carton-lisos',
                'short_desc' => 'Suministro de vasos de carton sin impresion.',
                'description' => 'Los vasos de carton lisos se entregan para operaciones de alto consumo con pedido minimo de 1.000 unidades y plazo de 25 dias habiles.',
            ],
            'printed-pet-cups' => [
                'name' => 'Vasos PET Impresos',
                'slug' => 'vasos-pet-impresos',
                'short_desc' => 'Vasos PET impresos para bebidas frias y marca visible.',
                'description' => 'Producimos vasos PET impresos con pedido minimo de 1.000 unidades. El plazo estandar es de 15 dias habiles y se adapta al volumen del proyecto.',
            ],
            'plain-pet-cups' => [
                'name' => 'Vasos PET Lisos',
                'slug' => 'vasos-pet-lisos',
                'short_desc' => 'Suministro de vasos PET sin impresion para operaciones rapidas.',
                'description' => 'Los vasos PET lisos se entregan desde 1.000 unidades. Son una alternativa practica para operaciones de servicio rapido y eventos.',
            ],
            'pet-cup-lids' => [
                'name' => 'Tapas para Vaso PET',
                'slug' => 'tapas-vaso-pet',
                'short_desc' => 'Tapas PET planas, clip y tipo domo.',
                'description' => 'Ofrecemos tapas para vaso PET en tres formatos: plana, clip y domo. Pedido minimo desde 1.000 unidades con suministro alineado al vaso.',
            ],
            'napkins-printed-plain' => [
                'name' => 'Servilletas (Impresas / Lisas)',
                'slug' => 'servilletas-impresas-lisas',
                'short_desc' => 'Produccion de servilletas para horeca con o sin impresion.',
                'description' => 'Las servilletas se producen en versiones impresas o lisas. Se manejan cajas de 2.400 o 4.800 unidades, con minimo de 20 cajas y plazo de 25 dias habiles.',
            ],
            'single-wet-wipes' => [
                'name' => 'Toallitas Humedas Unitarias',
                'slug' => 'toallitas-humedas-unitarias',
                'short_desc' => 'Produccion de toallitas humedas en formato individual.',
                'description' => 'Fabricamos toallitas humedas solo en formato unitario, impresas o lisas. El minimo es 10.000 unidades y para impresiones de 4+ colores el minimo es 20.000.',
            ],
            'flag-toothpicks-standard' => [
                'name' => 'Palillos con Bandera (Estandar)',
                'slug' => 'palillos-bandera-estandar',
                'short_desc' => 'Palillos con bandera en medida estandar.',
                'description' => 'Palillos con bandera en medida estandar, con opcion de impresion a una o dos caras. Ideal para presentaciones de mesa y eventos promocionales.',
            ],
            'custom-size-flag-toothpicks' => [
                'name' => 'Palillos con Bandera a Medida',
                'slug' => 'palillos-bandera-medida',
                'short_desc' => 'Palillos con bandera personalizados por medida y diseño.',
                'description' => 'Producimos palillos con bandera en medidas especiales con impresion a una o dos caras. Se ajusta a campañas y requerimientos de marca.',
            ],
            'stick-sugar' => [
                'name' => 'Azucar en Stick (Impresa / Lisa)',
                'slug' => 'azucar-stick',
                'short_desc' => 'Azucar en stick blanca o morena, impresa o lisa.',
                'description' => 'Suministro de azucar en stick con opciones blanca o morena. Puede elegirse empaque impreso o liso, con pedido minimo de 20.000 unidades.',
            ],
            'sticker-printing-service' => [
                'name' => 'Servicio de Impresion de Stickers',
                'slug' => 'servicio-impresion-stickers',
                'short_desc' => 'Impresion de stickers en medidas solicitadas.',
                'description' => 'Ofrecemos impresion de stickers en medidas personalizadas con plazo estandar de 10 dias habiles para aplicaciones de empaque y promocion.',
            ],
        ];

        $products = Product::query()->with('translations')->get();
        foreach ($products as $product) {
            $en = $product->translations->firstWhere('lang', 'en');
            if (!$en) {
                continue;
            }

            $seed = $map[$en->slug] ?? [
                'name' => $en->name,
                'slug' => $en->slug,
                'short_desc' => $en->short_desc ?: $en->name,
                'description' => $en->description ?: $en->short_desc,
            ];

            $product->translations()->updateOrCreate(
                ['lang' => 'es'],
                [
                    'name' => $seed['name'],
                    'slug' => $seed['slug'],
                    'short_desc' => $seed['short_desc'],
                    'description' => $seed['description'],
                    'seo_title' => mb_substr($seed['name'] . ' | Lunar Ambalaj', 0, 60),
                    'seo_desc' => mb_substr($seed['short_desc'], 0, 160),
                ],
            );

            $specs = $product->specs;
            if (!is_array($specs)) {
                continue;
            }

            if (isset($specs['en']) && is_array($specs['en'])) {
                $specs['es'] = $this->localizeSpecRows($specs['en']);
                $product->update(['specs' => $specs]);
            }
        }
    }

    /**
     * @param array<string, mixed> $rows
     * @return array<string, string>
     */
    private function localizeSpecRows(array $rows): array
    {
        $keyMap = [
            'Size' => 'Tamano',
            'Printing' => 'Impresion',
            'Packaging' => 'Empaque',
            'Min. Order' => 'Pedido minimo',
            'Lead Time' => 'Plazo',
            'Model' => 'Modelo',
            'Type' => 'Tipo',
            'Lid' => 'Tapa',
            'Carton' => 'Caja',
        ];

        $valueMap = [
            'Printed / plain' => 'Impreso / sin impresion',
            'Printed/plain' => 'Impreso / sin impresion',
            'Plain' => 'Liso',
            'Wrapped/unwrapped' => 'Con envoltorio/sin envoltorio',
            'business days' => 'dias habiles',
            'units' => 'unidades',
            'White/brown' => 'Blanco/moreno',
            'Flat, clip and dome' => 'Plana, clip y domo',
        ];

        $localized = [];
        foreach ($rows as $key => $value) {
            if (!is_scalar($value)) {
                continue;
            }

            $translatedKey = $keyMap[(string) $key] ?? (string) $key;
            $translatedValue = (string) $value;
            foreach ($valueMap as $from => $to) {
                $translatedValue = str_replace($from, $to, $translatedValue);
            }

            $localized[$translatedKey] = $translatedValue;
        }

        return $localized;
    }

    private function upsertPostTranslations(): void
    {
        $map = [
            'critical-criteria-when-selecting-plastic-frozen-straws' => [
                'title' => 'Criterios clave para elegir pajitas plasticas frozen',
                'short_desc' => 'Guia para seleccionar diametro, material e impresion en pajitas plasticas frozen para operaciones horeca.',
                'body' => "Al elegir pajitas plasticas frozen, los puntos criticos son diametro, resistencia y consistencia de impresion.\n\nSeleccion de diametro: para bebidas con mayor viscosidad recomendamos desde 8 mm. Para refrescos estandar puede trabajarse con 6 mm.\n\nCalidad de material: se debe usar materia prima apta para contacto con alimentos y control de lote en produccion.\n\nImpresion: con impresion CMYK se puede alinear logotipo y colores corporativos en alto volumen.\n\nSi desea una recomendacion por tipo de bebida y formato de servicio, puede solicitar oferta desde el formulario de cotizacion.",
            ],
            'size-and-usage-guide-for-multifunction-straws' => [
                'title' => 'Guia de medidas y uso para pajitas corrugadas',
                'short_desc' => 'Medidas recomendadas y escenarios de uso para pajitas corrugadas en cafe, fast-food y hoteleria.',
                'body' => "Las pajitas corrugadas se usan cuando se necesita comodidad de consumo y estabilidad en servicio rapido.\n\nMedida recomendada: 8x24 cm para operaciones estandar de bebidas frias.\n\nAplicaciones: menu frio en cafeterias, combos en fast-food y servicio en hoteleria.\n\nEmpaque y presentacion: puede elegirse con o sin envoltorio segun requisito de higiene y velocidad de operacion.",
            ],
            'moq-management-in-b2b-packaging-supply' => [
                'title' => 'Gestion de MOQ en suministro B2B de empaques',
                'short_desc' => 'Como planificar MOQ para controlar costos, stock y plazo en compras B2B de productos de servicio.',
                'body' => "La gestion de MOQ en compras B2B impacta directamente en costo unitario, frecuencia de reabastecimiento y riesgo de rotura de stock.\n\nPractica recomendada: combinar categorias en una sola planificacion (pajitas + vasos + servilletas) para equilibrar volumen y frecuencia.\n\nAdemas del MOQ, conviene definir desde el inicio requisitos de impresion, empaque y calendario logístico para evitar cambios de ultimo minuto.",
            ],
        ];

        $posts = Post::query()->with('translations')->get();
        foreach ($posts as $post) {
            $en = $post->translations->firstWhere('lang', 'en');
            if (!$en) {
                continue;
            }

            $seed = $map[$en->slug] ?? [
                'title' => $en->title,
                'short_desc' => $en->short_desc ?: $en->title,
                'body' => $en->body,
            ];

            $post->translations()->updateOrCreate(
                ['lang' => 'es'],
                [
                    'title' => $seed['title'],
                    'slug' => $en->slug,
                    'short_desc' => $seed['short_desc'],
                    'body' => $seed['body'],
                    'seo_title' => mb_substr($seed['title'] . ' | Blog Lunar Ambalaj', 0, 60),
                    'seo_desc' => mb_substr($seed['short_desc'], 0, 160),
                ],
            );
        }
    }

    private function upsertFaqTranslations(): void
    {
        $map = [
            'What is the minimum order quantity?' => [
                'q' => 'Cual es el pedido minimo?',
                'a' => 'El MOQ base es 5.000 unidades, pero puede variar segun producto y tipo de impresion.',
            ],
            'What print file format is required?' => [
                'q' => 'Que formato de archivo de impresion se requiere?',
                'a' => 'Recomendamos PDF, AI o archivos vectoriales de alta resolucion con colores CMYK.',
            ],
            'How long is the lead time?' => [
                'q' => 'Cuanto tarda el plazo de produccion?',
                'a' => 'El plazo se define en la oferta segun cantidad total, mezcla de productos y carga de produccion.',
            ],
            'Can I request samples?' => [
                'q' => 'Puedo solicitar muestras?',
                'a' => 'Si. Podemos organizar un flujo de muestra segun categoria y alcance del proyecto.',
            ],
            'Do you provide wrapped options?' => [
                'q' => 'Ofrecen opciones con envoltorio individual?',
                'a' => 'Si, hay alternativas con envoltorio individual en grupos de pajitas y toallitas humedas.',
            ],
            'What sizes are available for plastic frozen straws?' => [
                'q' => 'Que medidas tienen las pajitas plasticas frozen?',
                'a' => 'Las medidas de diametro y largo se definen en mm segun tipo de bebida y formato de vaso.',
            ],
            'Do you offer custom-size straw production?' => [
                'q' => 'Producen pajitas en medidas personalizadas?',
                'a' => 'Si, trabajamos medidas especiales por proyecto con combinaciones de impresion y empaque.',
            ],
            'Do you produce paper straws?' => [
                'q' => 'Producen pajitas de papel?',
                'a' => 'Las pajitas de papel se suministran en modelo de maquila y bajo solicitud.',
            ],
            'Can flag toothpicks be single or double side printed?' => [
                'q' => 'Los palillos con bandera pueden imprimirse por una o dos caras?',
                'a' => 'Si, se puede aplicar impresion de una cara o doble cara segun el diseño aprobado.',
            ],
            'Do you print logos on stick sugar packaging?' => [
                'q' => 'Imprimen logotipo en el empaque de azucar en stick?',
                'a' => 'Si, ofrecemos azucar en stick con impresion de marca y adaptacion a colores corporativos.',
            ],
            'How is carton quantity determined?' => [
                'q' => 'Como se determina la cantidad por caja?',
                'a' => 'Se define por medida del producto y tipo de empaque, y se confirma en la oferta.',
            ],
            'Do you ship internationally?' => [
                'q' => 'Realizan envios internacionales?',
                'a' => 'Si, el metodo de entrega y la documentacion de exportacion se aclaran durante la oferta.',
            ],
        ];

        $faqs = Faq::query()->with('translations')->get();
        foreach ($faqs as $faq) {
            $en = $faq->translations->firstWhere('lang', 'en');
            if (!$en) {
                continue;
            }

            $seed = $map[$en->question] ?? [
                'q' => $en->question,
                'a' => $en->answer,
            ];

            $faq->translations()->updateOrCreate(
                ['lang' => 'es'],
                [
                    'question' => $seed['q'],
                    'answer' => $seed['a'],
                ],
            );
        }
    }

    private function upsertTestimonialTranslations(): void
    {
        $items = Testimonial::query()->with('translations')->get();
        foreach ($items as $item) {
            $en = $item->translations->firstWhere('lang', 'en');
            if (!$en) {
                continue;
            }

            $item->translations()->updateOrCreate(
                ['lang' => 'es'],
                ['content' => $en->content],
            );
        }
    }

    private function kvkkBody(): string
    {
        return '<p>Este aviso informa como se tratan los datos personales recopilados mediante formularios del sitio web y canales de comunicacion comercial.</p><h2>1. Responsable del tratamiento</h2><p><strong>Lunar Ambalaj - Tulgahan Yılkın</strong></p><h2>2. Categorias de datos personales</h2><ul><li>nombre y apellido</li><li>telefono</li><li>correo electronico</li><li>nombre de empresa</li><li>mensaje / detalles de cotizacion</li><li>direccion IP</li><li>informacion del dispositivo</li><li>datos de cookies</li></ul><h2>3. Finalidades del tratamiento</h2><ul><li>gestion de solicitudes de cotizacion</li><li>comunicacion con clientes</li><li>ejecucion de procesos de servicio</li><li>seguridad del sitio web</li><li>cumplimiento de obligaciones legales</li><li>analitica y mejora del servicio</li><li>actividades de marketing (solo con consentimiento)</li></ul><h2>4. Transferencia de datos</h2><p>Los datos pueden compartirse, cuando sea necesario, con proveedores de hosting, proveedores de correo electronico y organismos publicos legalmente autorizados.</p><h2>5. Conservacion</h2><p>Los datos personales se conservan unicamente durante el tiempo requerido por las obligaciones legales y las finalidades del tratamiento. No se garantiza un periodo fijo para todos los casos.</p><h2>6. Derechos del titular</h2><p>El titular puede solicitar acceso, correccion, supresion y oposicion, de forma equivalente a los derechos previstos en el Articulo 11 de KVKK.</p><h2>7. Canal de contacto</h2><p><a href="mailto:info@lunarambalaj.com.tr">info@lunarambalaj.com.tr</a></p>';
    }

    private function privacyBody(): string
    {
        return '<p>Esta Politica de Privacidad describe el tratamiento de datos personales y las medidas generales de seguridad aplicadas por Lunar Ambalaj - Tulgahan Yılkın.</p><h2>1. Responsable del tratamiento</h2><p><strong>Lunar Ambalaj - Tulgahan Yılkın</strong></p><h2>2. Datos tratados</h2><ul><li>nombre y apellido</li><li>telefono</li><li>correo electronico</li><li>empresa</li><li>contenido de mensaje y solicitud de oferta</li><li>IP, dispositivo y cookies</li></ul><h2>3. Finalidades</h2><ul><li>cotizaciones y seguimiento comercial</li><li>comunicacion con cliente y postventa</li><li>continuidad operativa y seguridad del sitio</li><li>cumplimiento normativo y legal</li><li>analitica para mejora de servicio</li><li>marketing solo con consentimiento expreso</li></ul><h2>4. Transferencias</h2><p>Las transferencias se limitan a proveedores tecnicos (hosting/correo) y autoridades legalmente competentes cuando exista obligacion.</p><h2>5. Conservacion</h2><p>La conservacion se determina por la finalidad del tratamiento y obligaciones legales aplicables; no existe promesa de plazo exacto universal.</p><h2>6. Derechos del usuario</h2><p>Puede ejercer derechos de acceso, rectificacion, eliminacion y oposicion por canal de contacto oficial.</p><h2>7. Contacto</h2><p><a href="mailto:info@lunarambalaj.com.tr">info@lunarambalaj.com.tr</a></p>';
    }

    private function cookieBody(): string
    {
        return '<p>Esta politica explica las categorias de cookies utilizadas en el sitio y como gestionarlas.</p><h2>1. Cookies esenciales</h2><p>Necesarias para funciones basicas del sitio y seguridad de sesion.</p><h2>2. Cookies analiticas</h2><p>Se utilizan para medir rendimiento, trafico y comportamiento agregado de uso.</p><h2>3. Cookies de marketing</h2><p>Solo se activan bajo marco de consentimiento cuando aplica una actividad publicitaria.</p><h2>4. Cookies de terceros</h2><p>Pueden establecerse por herramientas de analitica, etiquetas de publicidad o plataformas sociales integradas.</p><h2>5. Gestion de cookies</h2><p>Puede bloquear o eliminar cookies desde la configuracion de su navegador. Esta accion puede afectar algunas funcionalidades del sitio.</p>';
    }

    private function distanceSalesBody(): string
    {
        return '<p><strong>No se realizan ventas ni pagos online directos a traves de nuestro sitio web. Este contrato se presenta solo con fines informativos.</strong></p><h2>1. Flujo comercial</h2><p>Las operaciones avanzan mediante solicitud de oferta, aprobacion comercial y confirmacion por escrito entre las partes.</p><h2>2. Entrega y planificacion</h2><p>Las condiciones de entrega pueden variar segun producto, volumen, personalizacion y capacidad de produccion.</p><h2>3. Excepcion para produccion personalizada</h2><p>Los productos fabricados con impresion personalizada, marca propia o especificaciones tecnicas particulares pueden no encajar en reglas estandar de desistimiento en ciertos supuestos. Esta redaccion es informativa y no constituye garantia legal absoluta.</p><h2>4. Contacto</h2><p><a href="mailto:info@lunarambalaj.com.tr">info@lunarambalaj.com.tr</a></p>';
    }

    private function termsBody(): string
    {
        return '<p>Estos Terminos de Uso regulan el acceso y uso general del sitio web.</p><h2>1. Condiciones de uso</h2><p>Al utilizar el sitio, el usuario acepta estas condiciones en su version vigente.</p><h2>2. Propiedad intelectual</h2><ul><li>Textos, imagenes, logotipos y composicion del sitio estan protegidos.</li><li>No se permite copia, reproduccion ni uso comercial no autorizado.</li></ul><h2>3. Limitacion de responsabilidad</h2><p>El contenido tiene finalidad informativa. Pueden existir limitaciones de responsabilidad por interrupciones tecnicas, enlaces de terceros o decisiones tomadas exclusivamente en base al contenido publicado.</p><h2>4. Actualizaciones</h2><p>Lunar Ambalaj puede revisar estos terminos y publicar versiones actualizadas cuando sea necesario.</p><h2>5. Contacto</h2><p><a href="mailto:info@lunarambalaj.com.tr">info@lunarambalaj.com.tr</a></p>';
    }
}

