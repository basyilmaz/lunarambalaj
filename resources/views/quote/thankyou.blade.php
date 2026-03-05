@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-4xl px-4 py-20">
    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-8">
        <h1 class="text-3xl font-bold text-emerald-800">{{ app()->getLocale()==='tr' ? 'Teşekkürler' : 'Thank You' }}</h1>
        <p class="mt-4 text-emerald-900">{{ app()->getLocale()==='tr' ? 'Teklif talebiniz başarıyla alındı. Ekip en kısa sürede teklif ve termin bilgisiyle dönüş yapacaktır.' : 'Your quote request has been received. Our team will get back shortly with quotation and lead-time details.' }}</p>

        <div class="mt-6 grid gap-3 md:grid-cols-3 text-sm">
            <div class="rounded-lg bg-white p-3 text-slate-700">1. {{ app()->getLocale()==='tr' ? 'Talep kaydı oluşturuldu' : 'Request registered' }}</div>
            <div class="rounded-lg bg-white p-3 text-slate-700">2. {{ app()->getLocale()==='tr' ? 'Kategori ve adet analizi' : 'Category and volume review' }}</div>
            <div class="rounded-lg bg-white p-3 text-slate-700">3. {{ app()->getLocale()==='tr' ? 'Teklif dönüşü' : 'Quotation response' }}</div>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ app()->getLocale()==='tr' ? '/' : '/en' }}" class="inline-block rounded bg-emerald-700 px-4 py-2 text-white">{{ app()->getLocale()==='tr' ? 'Anasayfaya Dön' : 'Back to Home' }}</a>
            <a href="{{ app()->getLocale()==='tr' ? '/urunler' : '/en/products' }}" class="inline-block rounded border border-emerald-700 px-4 py-2 text-emerald-800">{{ app()->getLocale()==='tr' ? 'Ürünleri İncele' : 'Explore Products' }}</a>
        </div>
    </div>
</section>
@endsection
