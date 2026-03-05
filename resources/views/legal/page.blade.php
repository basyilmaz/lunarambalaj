@extends('layouts.app')

@section('content')
@php
    $locale = app()->getLocale();
    $isRtl = $locale === 'ar';
@endphp

<section class="bg-slate-50 py-10 md:py-14">
    <div class="mx-auto max-w-7xl px-4">
        <div class="grid gap-8 lg:grid-cols-4">
            <aside class="lg:col-span-1">
                <nav class="sticky top-24 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <h2 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                        {{ $locale === 'tr' ? 'Yasal Sayfalar' : ($locale === 'ru' ? 'Правовые страницы' : ($locale === 'ar' ? 'الصفحات القانونية' : 'Legal Pages')) }}
                    </h2>
                    <ul class="space-y-2">
                        @foreach($legalLinks as $link)
                            <li>
                                <a
                                    href="{{ route($link['route']) }}"
                                    class="block rounded-md px-3 py-2 text-sm transition {{ request()->routeIs($link['route']) ? 'bg-amber-100 text-amber-900' : 'text-slate-700 hover:bg-slate-100' }}"
                                >
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </aside>

            <article class="lg:col-span-3 rounded-xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                <h1 class="text-2xl font-bold text-slate-900 md:text-3xl {{ $isRtl ? 'text-right' : '' }}">{{ $pageTitle }}</h1>
                <p class="mt-3 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 {{ $isRtl ? 'text-right' : '' }}">
                    {{ $legalNotice }}
                </p>

                <div class="prose prose-slate mt-6 max-w-none prose-headings:font-semibold prose-h2:mt-8 prose-h2:text-xl prose-li:my-1 {{ $isRtl ? 'text-right' : '' }}">
                    {!! $pageBody !!}
                </div>
            </article>
        </div>
    </div>
</section>
@endsection

