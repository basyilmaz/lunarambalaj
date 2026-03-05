@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-4xl px-4 py-16">
    <h1 class="text-3xl font-bold">{{ app()->getLocale()==='tr' ? 'Teklif Al' : 'Get Quote' }}</h1>
    <p class="mt-3 text-slate-600">{{ app()->getLocale()==='tr' ? 'Kategori, ürün, adet ve baskı bilgilerini paylaşın. Ekibimiz en kısa sürede teklif ve termin detayını iletsin.' : 'Share category, product, quantity and print requirements. Our team will reply with quote and lead-time details shortly.' }}</p>

    <form method="post" enctype="multipart/form-data" class="mt-8 space-y-4 rounded-xl border border-slate-200 bg-white p-6">
        @csrf
        <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
        <input type="hidden" name="utm_source" value="{{ old('utm_source', request('utm_source', $attribution['utm_source'] ?? '')) }}">
        <input type="hidden" name="utm_medium" value="{{ old('utm_medium', request('utm_medium', $attribution['utm_medium'] ?? '')) }}">
        <input type="hidden" name="utm_campaign" value="{{ old('utm_campaign', request('utm_campaign', $attribution['utm_campaign'] ?? '')) }}">
        <input type="hidden" name="utm_term" value="{{ old('utm_term', request('utm_term', $attribution['utm_term'] ?? '')) }}">
        <input type="hidden" name="utm_content" value="{{ old('utm_content', request('utm_content', $attribution['utm_content'] ?? '')) }}">
        <input type="hidden" name="gclid" value="{{ old('gclid', request('gclid', $attribution['gclid'] ?? '')) }}">
        <input type="hidden" name="fbclid" value="{{ old('fbclid', request('fbclid', $attribution['fbclid'] ?? '')) }}">

        <div class="grid gap-4 md:grid-cols-2">
            <input class="w-full rounded border border-slate-300 px-3 py-2" name="name" placeholder="{{ app()->getLocale()==='tr' ? 'Ad Soyad' : 'Name' }}" value="{{ old('name') }}" required>
            <input class="w-full rounded border border-slate-300 px-3 py-2" name="company" placeholder="{{ app()->getLocale()==='tr' ? 'Firma' : 'Company' }}" value="{{ old('company') }}">
            <input class="w-full rounded border border-slate-300 px-3 py-2" name="phone" placeholder="Phone" value="{{ old('phone') }}" required>
            <input class="w-full rounded border border-slate-300 px-3 py-2" name="email" type="email" placeholder="Email" value="{{ old('email') }}" required>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <select class="w-full rounded border border-slate-300 px-3 py-2" name="product_category" required>
                <option value="">{{ app()->getLocale()==='tr' ? 'Kategori seçin' : 'Select category' }}</option>
                @foreach($categories as $category)
                    @php $ct = $category->translation(app()->getLocale()); @endphp
                    @if($ct)
                        <option value="{{ $ct->name }}" @selected(old('product_category', request('category'))===$ct->name)>{{ $ct->name }}</option>
                    @endif
                @endforeach
            </select>

            <select class="w-full rounded border border-slate-300 px-3 py-2" name="product">
                <option value="">{{ app()->getLocale()==='tr' ? 'Ürün seçin (opsiyonel)' : 'Select product (optional)' }}</option>
                @foreach($products as $product)
                    @php $pt = $product->translation(app()->getLocale()); @endphp
                    @if($pt)
                        <option value="{{ $pt->name }}" @selected(old('product', request('product'))===$pt->name)>{{ $pt->name }}</option>
                    @endif
                @endforeach
            </select>

            <input class="w-full rounded border border-slate-300 px-3 py-2" name="quantity" type="number" min="1" value="{{ old('quantity', 5000) }}" required>
            <input class="w-full rounded border border-slate-300 px-3 py-2" name="delivery_city" value="{{ old('delivery_city') }}" placeholder="{{ app()->getLocale()==='tr' ? 'Teslimat şehri' : 'Delivery city' }}" required>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <p class="mb-2 text-sm font-medium text-slate-700">{{ app()->getLocale()==='tr' ? 'Baskı gerekli mi?' : 'Print needed?' }}</p>
                <label class="mr-4 inline-flex items-center gap-2 text-sm"><input type="radio" name="print_needed" value="yes" @checked(old('print_needed', 'yes')==='yes')> {{ app()->getLocale()==='tr' ? 'Evet' : 'Yes' }}</label>
                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="print_needed" value="no" @checked(old('print_needed')==='no')> {{ app()->getLocale()==='tr' ? 'Hayır' : 'No' }}</label>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-slate-700">{{ app()->getLocale()==='tr' ? 'Jelatin / tekli sarım gerekli mi?' : 'Wrapping needed?' }}</p>
                <label class="mr-4 inline-flex items-center gap-2 text-sm"><input type="radio" name="wrapping_needed" value="yes" @checked(old('wrapping_needed', 'no')==='yes')> {{ app()->getLocale()==='tr' ? 'Evet' : 'Yes' }}</label>
                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="wrapping_needed" value="no" @checked(old('wrapping_needed', 'no')==='no')> {{ app()->getLocale()==='tr' ? 'Hayır' : 'No' }}</label>
            </div>
        </div>

        <input class="w-full rounded border border-slate-300 px-3 py-2" name="attachment" type="file" accept=".pdf,.png,.jpg,.jpeg,.webp,.svg">

        <textarea class="w-full rounded border border-slate-300 px-3 py-2" name="message" rows="4" placeholder="{{ app()->getLocale()==='tr' ? 'Notunuz (opsiyonel)' : 'Message (optional)' }}">{{ old('message') }}</textarea>

        <p class="text-xs text-slate-500">{{ app()->getLocale()==='tr' ? 'Not: 5000 adet altı talepler de değerlendirilir; birim maliyet değişebilir.' : 'Note: Requests below 5000 units can still be evaluated; unit cost may vary.' }}</p>

        @php
            $locale = app()->getLocale();
            $kvkkLinks = [
                'tr' => '/kvkk',
                'en' => '/en/kvkk',
                'ru' => '/ru/kvkk',
                'ar' => '/ar/kvkk',
            ];
            $kvkkConsentText = [
                'tr' => "KVKK kapsamında kişisel verilerimin işlenmesine izin veriyorum. <a class=\"underline\" href=\"{$kvkkLinks['tr']}\" target=\"_blank\" rel=\"noopener\">KVKK Aydınlatma Metni</a>'ni okudum.",
                'en' => "I consent to the processing of my personal data under KVKK. I have read the <a class=\"underline\" href=\"{$kvkkLinks['en']}\" target=\"_blank\" rel=\"noopener\">Privacy Notice (KVKK)</a>.",
                'ru' => "Я даю согласие на обработку моих персональных данных в рамках KVKK. Я ознакомился(лась) с <a class=\"underline\" href=\"{$kvkkLinks['ru']}\" target=\"_blank\" rel=\"noopener\">Уведомлением о защите данных (KVKK)</a>.",
                'ar' => "أوافق على معالجة بياناتي الشخصية وفقًا لـ KVKK، وقد قرأت <a class=\"underline\" href=\"{$kvkkLinks['ar']}\" target=\"_blank\" rel=\"noopener\">إشعار حماية البيانات (KVKK)</a>.",
            ];
        @endphp
        <label class="flex items-start gap-2 text-sm">
            <input type="checkbox" name="kvkk" value="1" required>
            <span>{!! $kvkkConsentText[$locale] ?? $kvkkConsentText['en'] !!}</span>
        </label>
        <button class="rounded-md bg-amber-500 px-4 py-2 font-semibold text-white">{{ app()->getLocale()==='tr' ? 'Teklif Gönder' : 'Submit Quote' }}</button>
    </form>
</section>
@endsection
