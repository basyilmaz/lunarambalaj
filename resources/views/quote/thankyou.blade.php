@extends('layouts.app')

@section('content')
@php
    $locale = app()->getLocale();
    $homeUrl = $locale === 'tr' ? '/' : '/' . $locale;
    $productsUrl = $locale === 'tr' ? '/urunler' : '/' . $locale . '/products';

    $copy = [
        'tr' => [
            'title' => 'Teşekkürler',
            'desc' => 'Teklif talebiniz başarıyla alındı. Ekibimiz en kısa sürede teklif ve termin bilgisiyle dönüş yapacaktır.',
            'step1' => 'Talep kaydı oluşturuldu',
            'step2' => 'Kategori ve adet analizi',
            'step3' => 'Teklif dönüşü',
            'home' => 'Anasayfaya Dön',
            'products' => 'Ürünleri İncele',
        ],
        'en' => [
            'title' => 'Thank You',
            'desc' => 'Your quote request has been received. Our team will get back shortly with quotation and lead-time details.',
            'step1' => 'Request registered',
            'step2' => 'Category and volume review',
            'step3' => 'Quotation response',
            'home' => 'Back to Home',
            'products' => 'Explore Products',
        ],
        'ru' => [
            'title' => 'Спасибо',
            'desc' => 'Ваш запрос получен. Наша команда скоро вернётся с расчётом и сроками.',
            'step1' => 'Заявка зарегистрирована',
            'step2' => 'Анализ категории и объёма',
            'step3' => 'Ответ с предложением',
            'home' => 'На главную',
            'products' => 'Смотреть продукты',
        ],
        'ar' => [
            'title' => 'شكرًا لك',
            'desc' => 'تم استلام طلب عرض السعر بنجاح. سيعود فريقنا إليك قريبًا بتفاصيل السعر والمدة.',
            'step1' => 'تم تسجيل الطلب',
            'step2' => 'تحليل الفئة والكمية',
            'step3' => 'إرسال عرض السعر',
            'home' => 'العودة للرئيسية',
            'products' => 'استعرض المنتجات',
        ],
        'es' => [
            'title' => 'Gracias',
            'desc' => 'Hemos recibido tu solicitud de cotización. Nuestro equipo te responderá pronto con precio y plazo.',
            'step1' => 'Solicitud registrada',
            'step2' => 'Análisis de categoría y cantidad',
            'step3' => 'Respuesta de cotización',
            'home' => 'Volver al Inicio',
            'products' => 'Ver Productos',
        ],
    ];

    $t = $copy[$locale] ?? $copy['en'];
@endphp

<section class="mx-auto max-w-4xl px-4 py-20">
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-8">
        <h1 class="text-3xl font-bold text-emerald-800">{{ $t['title'] }}</h1>
        <p class="mt-4 text-emerald-900">{{ $t['desc'] }}</p>

        <div class="mt-6 grid gap-3 text-sm md:grid-cols-3">
            <div class="rounded-lg bg-white p-3 text-slate-700">1. {{ $t['step1'] }}</div>
            <div class="rounded-lg bg-white p-3 text-slate-700">2. {{ $t['step2'] }}</div>
            <div class="rounded-lg bg-white p-3 text-slate-700">3. {{ $t['step3'] }}</div>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ $homeUrl }}" class="inline-block rounded bg-emerald-700 px-4 py-2 text-white">{{ $t['home'] }}</a>
            <a href="{{ $productsUrl }}" class="inline-block rounded border border-emerald-700 px-4 py-2 text-emerald-800">{{ $t['products'] }}</a>
        </div>
    </div>
</section>
@endsection

