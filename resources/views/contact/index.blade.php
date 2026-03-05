@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<x-hero
    :subtitle="__('site.contact.subtitle')"
    :title="__('site.contact.hero_title')"
    height="min-h-[400px]"
>
    <p class="text-xl text-slate-300 mb-10 max-w-2xl font-light leading-relaxed">
        {{ __('site.contact.hero_subtitle') }}
    </p>
</x-hero>

<!-- Contact Methods -->
<section class="bg-slate-50 py-12">
    <div class="mx-auto max-w-7xl px-4">
        <div class="grid gap-6 md:grid-cols-3">
            <!-- Phone -->
            <div class="bg-white p-6 border-l-4 border-primary-yellow" data-aos="fade-up">
                <svg class="w-10 h-10 text-primary-yellow mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">{{ __('site.contact.phone_label') }}</p>
                <a href="tel:{{ preg_replace('/\s+/', '', (string) $siteSetting?->phone) }}" class="text-lg font-bold text-slate-900 hover:text-primary-yellow" data-track="phone">
                    {{ $siteSetting?->phone }}
                </a>
            </div>

            <!-- Email -->
            <div class="bg-white p-6 border-l-4 border-info-blue" data-aos="fade-up" data-aos-delay="100">
                <svg class="w-10 h-10 text-info-blue mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">{{ __('site.contact.email_label') }}</p>
                <a href="mailto:{{ $siteSetting?->email }}" class="text-lg font-bold text-slate-900 hover:text-info-blue block">
                    {{ $siteSetting?->email }}
                </a>
                @if($siteSetting?->email_secondary)
                    <a href="mailto:{{ $siteSetting->email_secondary }}" class="text-sm text-slate-600 hover:text-info-blue block mt-1">
                        {{ $siteSetting->email_secondary }}
                    </a>
                @endif
            </div>

            <!-- WhatsApp -->
            <div class="bg-white p-6 border-l-4 border-success-green" data-aos="fade-up" data-aos-delay="200">
                <svg class="w-10 h-10 text-success-green mb-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-2">WhatsApp</p>
                @if($siteSetting?->whatsapp)
                    <a href="https://wa.me/{{ $siteSetting->whatsapp }}?text={{ urlencode(__('site.common.whatsapp_message')) }}" target="_blank" class="text-lg font-bold text-slate-900 hover:text-success-green" data-track="whatsapp">
                        {{ __('site.contact.whatsapp_chat') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Contact Form & Info -->
<section class="py-16 bg-white">
    <div class="mx-auto max-w-7xl px-4">
        <div class="grid lg:grid-cols-5 gap-12">
            <!-- Contact Form -->
            <div class="lg:col-span-3">
                <h2 class="text-3xl font-bold text-slate-900 font-heading uppercase mb-6">
                    {{ __('site.contact.send_message') }}
                </h2>

                @if(session('success'))
                    <div class="mb-6 bg-success-green/10 border-l-4 border-success-green p-4 rounded">
                        <p class="text-success-green font-bold">{{ session('success') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <p class="text-red-700 font-bold">{{ __('site.contact.fix_errors') }}</p>
                        <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="post" class="space-y-5">
                    @csrf
                    <!-- Honeypot -->
                    <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">

                    <!-- UTM Parameters -->
                    <input type="hidden" name="utm_source" value="{{ old('utm_source', request('utm_source', $attribution['utm_source'] ?? '')) }}">
                    <input type="hidden" name="utm_medium" value="{{ old('utm_medium', request('utm_medium', $attribution['utm_medium'] ?? '')) }}">
                    <input type="hidden" name="utm_campaign" value="{{ old('utm_campaign', request('utm_campaign', $attribution['utm_campaign'] ?? '')) }}">
                    <input type="hidden" name="utm_term" value="{{ old('utm_term', request('utm_term', $attribution['utm_term'] ?? '')) }}">
                    <input type="hidden" name="utm_content" value="{{ old('utm_content', request('utm_content', $attribution['utm_content'] ?? '')) }}">
                    <input type="hidden" name="gclid" value="{{ old('gclid', request('gclid', $attribution['gclid'] ?? '')) }}">
                    <input type="hidden" name="fbclid" value="{{ old('fbclid', request('fbclid', $attribution['fbclid'] ?? '')) }}">

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-bold text-slate-900 uppercase tracking-wide mb-2">
                            {{ __('site.contact.full_name') }} <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded focus:border-primary-yellow focus:outline-none transition-colors @error('name') border-red-500 @enderror"
                            placeholder="{{ __('site.contact.full_name_placeholder') }}"
                            required
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-bold text-slate-900 uppercase tracking-wide mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded focus:border-primary-yellow focus:outline-none transition-colors @error('email') border-red-500 @enderror"
                            placeholder="{{ __('site.contact.email_placeholder') }}"
                            required
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-bold text-slate-900 uppercase tracking-wide mb-2">
                            {{ __('site.contact.phone_label') }}
                        </label>
                        <input
                            type="tel"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded focus:border-primary-yellow focus:outline-none transition-colors @error('phone') border-red-500 @enderror"
                            placeholder="{{ __('site.contact.phone_placeholder') }}"
                        >
                        @error('phone')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-sm font-bold text-slate-900 uppercase tracking-wide mb-2">
                            {{ __('site.contact.message_label') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            name="message"
                            rows="6"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded focus:border-primary-yellow focus:outline-none transition-colors @error('message') border-red-500 @enderror"
                            placeholder="{{ __('site.contact.message_placeholder') }}"
                            required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- KVKK Checkbox -->
                    <div>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input
                                type="checkbox"
                                name="kvkk"
                                value="1"
                                class="mt-1 w-5 h-5 border-2 border-slate-300 rounded text-primary-yellow focus:ring-primary-yellow"
                                required
                            >
                            <span class="text-sm text-slate-600 group-hover:text-slate-900">
                                {{ __('site.contact.kvkk_text') }}
                                <a href="{{ route(app()->getLocale() . '.kvkk') }}" class="text-primary-yellow underline" target="_blank">
                                    {{ __('site.contact.kvkk_details') }}
                                </a>
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <x-button variant="primary" type="submit" size="lg" class="w-full md:w-auto">
                        {{ __('site.contact.send_button') }}
                        <svg class="w-5 h-5 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </x-button>
                </form>
            </div>

            <!-- Info & Map -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Address & Hours -->
                <div class="bg-slate-50 p-6 rounded-lg border-l-4 border-primary-yellow">
                    <h3 class="text-xl font-bold text-slate-900 font-heading uppercase mb-4">
                        {{ __('site.contact.address_hours') }}
                    </h3>

                    <div class="space-y-4 text-sm text-slate-700">
                        <div>
                            <p class="font-bold text-slate-900 mb-1">{{ __('site.contact.address_label') }}:</p>
                            <p class="leading-relaxed">{{ $siteSetting?->address }}</p>
                        </div>

                        <div>
                            <p class="font-bold text-slate-900 mb-1">{{ __('site.contact.working_hours_label') }}:</p>
                            <p class="leading-relaxed">{!! nl2br(e((string) $siteSetting?->working_hours)) !!}</p>
                        </div>
                    </div>
                </div>

                <!-- Map -->
                <div class="overflow-hidden rounded-lg shadow-lg">
                    @php
                        if ($siteSetting?->latitude && $siteSetting?->longitude) {
                            $mapSrc = "https://www.google.com/maps?q={$siteSetting->latitude},{$siteSetting->longitude}&output=embed";
                        } else {
                            $mapSrc = "https://www.google.com/maps?q=" . urlencode((string) $siteSetting?->address) . "&output=embed";
                        }
                    @endphp
                    <iframe
                        title="Lunar Ambalaj Map"
                        width="100%"
                        height="350"
                        class="border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="{{ $mapSrc }}">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4">
        <x-section-header
            :subtitle="__('site.contact.faq_subtitle')"
            :title="__('site.contact.faq_title')"
            align="center"
        />

        <div class="grid md:grid-cols-2 gap-6 mt-12 max-w-5xl mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-bold text-slate-900 mb-2">{{ __('site.contact.response_time_q') }}</h3>
                <p class="text-sm text-slate-600">{{ __('site.contact.response_time_a') }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-bold text-slate-900 mb-2">{{ __('site.contact.languages_q') }}</h3>
                <p class="text-sm text-slate-600">{{ __('site.contact.languages_a') }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-bold text-slate-900 mb-2">{{ __('site.contact.samples_q') }}</h3>
                <p class="text-sm text-slate-600">{{ __('site.contact.samples_a') }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-bold text-slate-900 mb-2">{{ __('site.contact.visit_q') }}</h3>
                <p class="text-sm text-slate-600">{{ __('site.contact.visit_a') }}</p>
            </div>
        </div>

        <div class="text-center mt-12">
            <x-button variant="outline" :href="route(app()->getLocale() . '.faq')">
                {{ __('site.contact.view_all_faqs') }}
                <svg class="w-4 h-4 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </x-button>
        </div>
    </div>
</section>

@endsection
