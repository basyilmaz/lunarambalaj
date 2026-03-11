@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-16">
    <h1 class="text-3xl font-bold">{{ app()->getLocale()==='tr' ? 'Galeri' : 'Gallery' }}</h1>
    <p class="mt-3 max-w-3xl text-slate-600">{{ app()->getLocale()==='tr' ? 'Üretim ve ürün galerisinde baskı, paketleme ve sunum senaryolarına uygun örnekleri inceleyebilirsiniz.' : 'Explore product and production visuals designed for printing, wrapping and service scenarios.' }}</p>
    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($products as $product)
            @php $t = $product->translation(app()->getLocale()); @endphp
            @if($t)
                @php $galleryImage = \App\Support\AssetVariant::optimized($product->image, 'images/product-printed.svg'); @endphp
                <figure class="rounded-xl border border-slate-200 bg-white p-5">
                    <img
                        loading="lazy"
                        src="{{ asset($galleryImage) }}"
                        alt="{{ $t->name }}"
                        class="mb-3 h-40 w-full rounded-lg object-cover"
                        width="640"
                        height="320"
                        decoding="async"
                    >
                    <figcaption class="font-semibold">{{ $t->name }}</figcaption>
                    <p class="mt-2 text-sm text-slate-600">{{ $t->short_desc }}</p>
                </figure>
            @endif
        @endforeach
    </div>
</section>
@endsection
