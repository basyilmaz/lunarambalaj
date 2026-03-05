@extends('layouts.app')

@section('content')

@php
    $isLegalPage = in_array($pageType, ['kvkk', 'cookie', 'privacy'], true);
@endphp

@if($isLegalPage)
    @php
        $isTr = app()->getLocale() === 'tr';
        $policyGroup = $isTr ? 'Yasal Politikalar' : 'Legal Policies';
        $policyNote = $isTr ? 'Veri talepleriniz için bizimle iletişime geçin.' : 'Contact us for data requests.';
        $contactLabel = $isTr ? 'İletişim' : 'Contact';
        $effective = $isTr ? 'Yürürlük: 01.01.2026' : 'Effective: 2026-01-01';
        $updated = $isTr ? 'Güncelleme: 01.03.2026' : 'Updated: 2026-03-01';
    @endphp

    <section class="relative min-h-[70vh] bg-slate-50 py-10 md:py-16">
        <div class="mx-auto max-w-6xl px-4">
            <div class="mb-4 overflow-x-auto md:hidden">
                <nav class="flex min-w-max gap-2 text-sm font-medium">
                    <a href="{{ route(app()->getLocale() . '.kvkk') }}" class="rounded-lg px-3 py-2 transition-colors {{ $pageType === 'kvkk' ? 'bg-primary-yellow/15 text-amber-700' : 'border border-slate-200 bg-white text-slate-700' }}">{{ __('site.footer.kvkk') }}</a>
                    <a href="{{ route(app()->getLocale() . '.privacy') }}" class="rounded-lg px-3 py-2 transition-colors {{ $pageType === 'privacy' ? 'bg-primary-yellow/15 text-amber-700' : 'border border-slate-200 bg-white text-slate-700' }}">{{ __('site.footer.privacy_policy') }}</a>
                    <a href="{{ route(app()->getLocale() . '.cookie') }}" class="rounded-lg px-3 py-2 transition-colors {{ $pageType === 'cookie' ? 'bg-primary-yellow/15 text-amber-700' : 'border border-slate-200 bg-white text-slate-700' }}">{{ __('site.footer.cookie_policy') }}</a>
                </nav>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-4 md:gap-8">
                <aside class="hidden md:block md:col-span-1">
                    <div class="sticky top-24 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h3 class="mb-4 text-xs font-bold uppercase tracking-wider text-slate-400">{{ $policyGroup }}</h3>
                        <nav class="space-y-2 text-sm font-medium">
                            <a href="{{ route(app()->getLocale() . '.kvkk') }}" class="block rounded-lg px-4 py-2.5 transition-colors {{ $pageType === 'kvkk' ? 'bg-primary-yellow/10 text-amber-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">{{ __('site.footer.kvkk') }}</a>
                            <a href="{{ route(app()->getLocale() . '.privacy') }}" class="block rounded-lg px-4 py-2.5 transition-colors {{ $pageType === 'privacy' ? 'bg-primary-yellow/10 text-amber-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">{{ __('site.footer.privacy_policy') }}</a>
                            <a href="{{ route(app()->getLocale() . '.cookie') }}" class="block rounded-lg px-4 py-2.5 transition-colors {{ $pageType === 'cookie' ? 'bg-primary-yellow/10 text-amber-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">{{ __('site.footer.cookie_policy') }}</a>
                        </nav>

                        <div class="mt-8 border-t border-slate-100 pt-6">
                            <p class="mb-3 text-xs text-slate-500">{{ $policyNote }}</p>
                            <a href="{{ route(app()->getLocale() . '.contact') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-xs font-bold uppercase tracking-wide text-white transition-colors hover:bg-slate-800">{{ $contactLabel }}</a>
                        </div>
                    </div>
                </aside>

                <div class="md:col-span-3">
                    <div class="flex items-start gap-4 rounded-t-xl border border-b-0 border-slate-200 bg-white p-5 pb-4 shadow-sm md:gap-6 md:p-8 md:pb-6">
                        <div class="hidden shrink-0 items-center justify-center rounded-2xl bg-amber-50 p-4 text-amber-600 sm:flex">
                            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h1 class="mb-2 text-2xl font-bold text-slate-900 md:text-3xl">{{ $pageTitle }}</h1>
                            <p class="text-sm text-slate-500">{{ $effective }} &bull; {{ $updated }}</p>
                        </div>
                    </div>

                    <div class="min-h-[380px] rounded-b-xl border border-slate-200 bg-white p-5 shadow-sm md:p-8">
                        <article class="prose prose-slate max-w-none break-words text-sm leading-relaxed prose-headings:font-heading prose-headings:text-slate-900">
                            {!! nl2br(e($pageBody)) !!}
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>
@else
    <section class="relative min-h-[300px] overflow-hidden bg-dark-charcoal py-16">
        <div class="absolute inset-0">
            <img src="{{ asset('images/hero-bg.png') }}" alt="About Background" class="h-full w-full object-cover opacity-20" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-dark-charcoal via-transparent to-dark-charcoal"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 text-center">
            <h1 class="text-3xl font-bold uppercase text-white md:text-5xl">{{ $pageTitle }}</h1>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 md:py-16">
        <div class="grid gap-10 lg:grid-cols-4">
            <div class="lg:col-span-3">
                <article class="prose prose-slate max-w-none prose-headings:font-heading prose-headings:text-slate-900 prose-p:leading-relaxed">
                    {!! nl2br(e($pageBody)) !!}
                </article>
            </div>

            <aside class="space-y-6">
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="mb-3 border-b border-slate-100 pb-3 text-sm font-bold uppercase tracking-wide text-slate-900">{{ __('site.menu.contact') }}</h3>
                    <div class="space-y-2 text-sm text-slate-600">
                        <p>{{ $siteSetting?->phone }}</p>
                        <p>{{ $siteSetting?->email }}</p>
                        @if($siteSetting?->email_secondary)
                            <p>{{ $siteSetting?->email_secondary }}</p>
                        @endif
                    </div>
                </div>

                <div class="rounded-xl bg-primary-yellow p-6 shadow-sm">
                    <h3 class="mb-2 text-sm font-bold uppercase tracking-wide text-dark-charcoal">{{ app()->getLocale() === 'tr' ? 'Hızlı Teklif' : 'Quick Quote' }}</h3>
                    <p class="mb-4 text-sm text-dark-charcoal/80">{{ app()->getLocale() === 'tr' ? 'Projeniz için kısa sürede teklif alın.' : 'Request a fast quote for your project.' }}</p>
                    <a href="{{ route(app()->getLocale() . '.quote') }}" class="inline-flex w-full items-center justify-center rounded-md bg-dark-charcoal px-4 py-2 text-xs font-bold uppercase tracking-wide text-white hover:bg-slate-800">{{ __('site.cta_quote') }}</a>
                </div>
            </aside>
        </div>
    </section>
@endif

@endsection
