@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-4xl px-4 py-16">
    <h1 class="text-3xl font-bold">{{ __('site.quote.hero_title') }}</h1>
    <p class="mt-3 text-slate-600">{{ __('site.quote.hero_subtitle') }}</p>

    <form method="post" enctype="multipart/form-data" class="mt-8 space-y-4 rounded-xl border border-slate-200 bg-white p-6">
        @csrf
        <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
        <input type="hidden" name="fg_nonce" value="{{ $botGuard['nonce'] ?? '' }}">
        <input type="hidden" name="fg_ts" value="{{ $botGuard['ts'] ?? '' }}">
        <input type="hidden" name="fg_sig" value="{{ $botGuard['sig'] ?? '' }}">
        <input type="hidden" name="utm_source" value="{{ old('utm_source', request('utm_source', $attribution['utm_source'] ?? '')) }}">
        <input type="hidden" name="utm_medium" value="{{ old('utm_medium', request('utm_medium', $attribution['utm_medium'] ?? '')) }}">
        <input type="hidden" name="utm_campaign" value="{{ old('utm_campaign', request('utm_campaign', $attribution['utm_campaign'] ?? '')) }}">
        <input type="hidden" name="utm_term" value="{{ old('utm_term', request('utm_term', $attribution['utm_term'] ?? '')) }}">
        <input type="hidden" name="utm_content" value="{{ old('utm_content', request('utm_content', $attribution['utm_content'] ?? '')) }}">
        <input type="hidden" name="gclid" value="{{ old('gclid', request('gclid', $attribution['gclid'] ?? '')) }}">
        <input type="hidden" name="fbclid" value="{{ old('fbclid', request('fbclid', $attribution['fbclid'] ?? '')) }}">

        <div class="grid gap-4 md:grid-cols-2">
            <input class="w-full rounded border border-slate-300 px-3 py-2" name="name" placeholder="{{ __('site.quote.name') }}" value="{{ old('name') }}" required>
            <input class="w-full rounded border border-slate-300 px-3 py-2" name="company" placeholder="{{ __('site.quote.company') }}" value="{{ old('company') }}">
            <input class="w-full rounded border border-slate-300 px-3 py-2" name="phone" placeholder="{{ __('site.quote.phone') }}" value="{{ old('phone') }}" required>
            <input class="w-full rounded border border-slate-300 px-3 py-2" name="email" type="email" placeholder="{{ __('site.quote.email') }}" value="{{ old('email') }}" required>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <select class="w-full rounded border border-slate-300 px-3 py-2" name="product_category" required>
                <option value="">{{ __('site.quote.select_category') }}</option>
                @foreach($categories as $category)
                    @php $categoryTranslation = $category->translation(app()->getLocale()); @endphp
                    @if($categoryTranslation)
                        <option value="{{ $categoryTranslation->name }}" @selected(old('product_category', request('category')) === $categoryTranslation->name)>
                            {{ $categoryTranslation->name }}
                        </option>
                    @endif
                @endforeach
            </select>

            <select class="w-full rounded border border-slate-300 px-3 py-2" name="product">
                <option value="">{{ __('site.quote.select_product') }}</option>
                @foreach($products as $product)
                    @php $productTranslation = $product->translation(app()->getLocale()); @endphp
                    @if($productTranslation)
                        <option value="{{ $productTranslation->name }}" @selected(old('product', request('product')) === $productTranslation->name)>
                            {{ $productTranslation->name }}
                        </option>
                    @endif
                @endforeach
            </select>

            <input class="w-full rounded border border-slate-300 px-3 py-2" name="quantity" type="number" min="1" value="{{ old('quantity', 5000) }}" placeholder="{{ __('site.quote.quantity') }}" required>
            <input class="w-full rounded border border-slate-300 px-3 py-2" name="delivery_city" value="{{ old('delivery_city') }}" placeholder="{{ __('site.quote.delivery_city') }}" required>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <p class="mb-2 text-sm font-medium text-slate-700">{{ __('site.quote.print_needed') }}</p>
                <label class="mr-4 inline-flex items-center gap-2 text-sm"><input type="radio" name="print_needed" value="yes" @checked(old('print_needed', 'yes') === 'yes')> {{ __('site.quote.yes') }}</label>
                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="print_needed" value="no" @checked(old('print_needed') === 'no')> {{ __('site.quote.no') }}</label>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-slate-700">{{ __('site.quote.wrapping_needed') }}</p>
                <label class="mr-4 inline-flex items-center gap-2 text-sm"><input type="radio" name="wrapping_needed" value="yes" @checked(old('wrapping_needed', 'no') === 'yes')> {{ __('site.quote.yes') }}</label>
                <label class="inline-flex items-center gap-2 text-sm"><input type="radio" name="wrapping_needed" value="no" @checked(old('wrapping_needed', 'no') === 'no')> {{ __('site.quote.no') }}</label>
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">{{ __('site.quote.attachment') }}</label>
            <input class="w-full rounded border border-slate-300 px-3 py-2" name="attachment" type="file" accept=".pdf,.png,.jpg,.jpeg,.webp,.svg">
        </div>

        <textarea class="w-full rounded border border-slate-300 px-3 py-2" name="message" rows="4" placeholder="{{ __('site.quote.message') }}">{{ old('message') }}</textarea>
        <p class="text-xs text-slate-500">{{ __('site.quote.note_text') }}</p>

        @php
            $locale = app()->getLocale();
            $kvkkLinks = [
                'tr' => '/kvkk',
                'en' => '/en/kvkk',
                'ru' => '/ru/kvkk',
                'ar' => '/ar/kvkk',
                'es' => '/es/kvkk',
            ];
            $kvkkConsentText = [
                'tr' => "KVKK kapsamında kişisel verilerimin işlenmesine izin veriyorum. <a class=\"underline\" href=\"{$kvkkLinks['tr']}\" target=\"_blank\" rel=\"noopener\">KVKK Aydınlatma Metni</a>'ni okudum.",
                'en' => "I consent to the processing of my personal data under KVKK. I have read the <a class=\"underline\" href=\"{$kvkkLinks['en']}\" target=\"_blank\" rel=\"noopener\">Privacy Notice (KVKK)</a>.",
                'ru' => "Я даю согласие на обработку моих персональных данных в рамках KVKK. Я ознакомился(лась) с <a class=\"underline\" href=\"{$kvkkLinks['ru']}\" target=\"_blank\" rel=\"noopener\">Уведомлением о защите данных (KVKK)</a>.",
                'ar' => "أوافق على معالجة بياناتي الشخصية وفقًا لـ KVKK، وقد قرأت <a class=\"underline\" href=\"{$kvkkLinks['ar']}\" target=\"_blank\" rel=\"noopener\">إشعار حماية البيانات (KVKK)</a>.",
                'es' => "Doy mi consentimiento para el tratamiento de mis datos personales conforme a KVKK. He leído el <a class=\"underline\" href=\"{$kvkkLinks['es']}\" target=\"_blank\" rel=\"noopener\">Aviso de Privacidad (KVKK)</a>.",
            ];
        @endphp
        <label class="flex items-start gap-2 text-sm">
            <input type="checkbox" name="kvkk" value="1" required>
            <span>{!! $kvkkConsentText[$locale] ?? $kvkkConsentText['en'] !!}</span>
        </label>

        <button class="rounded-md bg-amber-500 px-4 py-2 font-semibold text-white">{{ __('site.quote.submit') }}</button>
    </form>
</section>
@endsection
