<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @php
        $gtmId = $siteSetting?->gtm_id ?: env('GTM_ID');
        $metaPixelId = $siteSetting?->meta_pixel_id ?: env('META_PIXEL_ID');
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $seo['title'] ?? 'Lunar Ambalaj' }}</title>
    <meta name="description" content="{{ $seo['description'] ?? '' }}">
    <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
    @foreach(($seo['alternates'] ?? []) as $hreflang => $href)
        <link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $href }}">
    @endforeach
    <meta property="og:type" content="{{ $seo['type'] ?? 'website' }}">
    <meta property="og:title" content="{{ $seo['title'] ?? '' }}">
    <meta property="og:description" content="{{ $seo['description'] ?? '' }}">
    <meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
    <meta property="og:image" content="{{ $seo['image'] ?? asset('images/hero-straw.svg') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['title'] ?? '' }}">
    <meta name="twitter:description" content="{{ $seo['description'] ?? '' }}">
    <meta name="twitter:image" content="{{ $seo['image'] ?? asset('images/hero-straw.svg') }}">
    @php
        $homeRouteNames = collect(config('site.locales', ['tr', 'en']))
            ->map(static fn (string $code): string => "{$code}.home")
            ->all();
        $isHomeRoute = request()->routeIs(...$homeRouteNames);
    @endphp
    @if($isHomeRoute)
        <link rel="preload" as="image" href="{{ asset('images/hero-bg.webp') }}" fetchpriority="high">
    @endif

    @if(app()->environment('production') && $gtmId)
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});
        var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{ $gtmId }}');
    </script>
    @endif

    @if(app()->environment('production') && $metaPixelId)
    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $metaPixelId }}');
        fbq('track', 'PageView');
    </script>
    @endif

    @foreach(($seo['jsonLd'] ?? []) as $schema)
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    @endforeach
    @php
        $breadcrumbSchema = null;
        $homePaths = collect(config('site.locales', ['tr', 'en']))
            ->map(static fn (string $code): string => $code === 'tr' ? '/' : $code)
            ->all();
        if (! in_array(request()->path(), $homePaths, true)) {
            $breadcrumbSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => rtrim(config('app.url'), '/') . (app()->getLocale() === 'en' ? '/en' : '/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => $seo['title'] ?? 'Page', 'item' => $seo['canonical'] ?? url()->current()],
                ],
            ];
        }
    @endphp
    @if($breadcrumbSchema)
        <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 overflow-x-hidden">
    @if(app()->environment('production') && $gtmId)
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    @php
        $locale = app()->getLocale();
        $menu = __('site.menu');
        $defaultLanguageLinks = [
            'tr' => '/',
            'en' => '/en',
            'ru' => '/ru',
            'ar' => '/ar',
        ];
        $languageLinks = $defaultLanguageLinks;
        $alternateLinks = $seo['alternates'] ?? [];

        if (isset($alternateLinks['tr-TR'])) {
            $languageLinks['tr'] = parse_url((string) $alternateLinks['tr-TR'], PHP_URL_PATH) ?: '/';
        }
        if (isset($alternateLinks['en'])) {
            $languageLinks['en'] = parse_url((string) $alternateLinks['en'], PHP_URL_PATH) ?: '/en';
        }
        if (isset($alternateLinks['ru'])) {
            $languageLinks['ru'] = parse_url((string) $alternateLinks['ru'], PHP_URL_PATH) ?: '/ru';
        }
        if (isset($alternateLinks['ar'])) {
            $languageLinks['ar'] = parse_url((string) $alternateLinks['ar'], PHP_URL_PATH) ?: '/ar';
        }

        $legalFooterLabels = [
            'tr' => [
                'kvkk' => 'KVKK',
                'privacy' => 'Gizlilik Politikası',
                'cookie' => 'Çerez Politikası',
                'terms' => 'Kullanım Şartları',
            ],
            'en' => [
                'kvkk' => 'KVKK / Privacy Notice',
                'privacy' => 'Privacy Policy',
                'cookie' => 'Cookie Policy',
                'terms' => 'Terms of Use',
            ],
            'ru' => [
                'kvkk' => 'Уведомление о защите данных',
                'privacy' => 'Политика конфиденциальности',
                'cookie' => 'Политика cookie',
                'terms' => 'Условия использования',
            ],
            'ar' => [
                'kvkk' => 'إشعار حماية البيانات',
                'privacy' => 'سياسة الخصوصية',
                'cookie' => 'سياسة ملفات تعريف الارتباط',
                'terms' => 'شروط الاستخدام',
            ],
        ];
        $legalLabels = $legalFooterLabels[$locale] ?? $legalFooterLabels['en'];

        // Override locale maps from config to keep switcher extensible (TR/EN/RU/AR/ES+).
        $supportedLocales = config('site.locales', ['tr', 'en']);
        $alternateLinks = $seo['alternates'] ?? [];
        $languageLinks = [];

        foreach ($supportedLocales as $supportedLocale) {
            $defaultPath = $supportedLocale === 'tr' ? '/' : '/' . $supportedLocale;
            $hreflang = $supportedLocale === 'tr' ? 'tr-TR' : $supportedLocale;

            $languageLinks[$supportedLocale] = isset($alternateLinks[$hreflang])
                ? (parse_url((string) $alternateLinks[$hreflang], PHP_URL_PATH) ?: $defaultPath)
                : $defaultPath;
        }

        $legalFooterLabels = [
            'tr' => [
                'kvkk' => 'KVKK',
                'privacy' => 'Gizlilik Politikası',
                'cookie' => 'Çerez Politikası',
                'terms' => 'Kullanım Şartları',
            ],
            'en' => [
                'kvkk' => 'KVKK / Privacy Notice',
                'privacy' => 'Privacy Policy',
                'cookie' => 'Cookie Policy',
                'terms' => 'Terms of Use',
            ],
            'ru' => [
                'kvkk' => 'Уведомление о защите данных',
                'privacy' => 'Политика конфиденциальности',
                'cookie' => 'Политика cookie',
                'terms' => 'Условия использования',
            ],
            'ar' => [
                'kvkk' => 'إشعار حماية البيانات',
                'privacy' => 'سياسة الخصوصية',
                'cookie' => 'سياسة ملفات تعريف الارتباط',
                'terms' => 'شروط الاستخدام',
            ],
            'es' => [
                'kvkk' => 'Aviso de Privacidad (KVKK)',
                'privacy' => 'Política de Privacidad',
                'cookie' => 'Política de Cookies',
                'terms' => 'Términos de Uso',
            ],
        ];
        $legalLabels = $legalFooterLabels[$locale] ?? $legalFooterLabels['en'];
    @endphp

    <!-- Top Bar -->
    <div class="bg-dark-charcoal text-light-gray py-2 text-xs">
        <div class="mx-auto max-w-7xl px-4 flex justify-between items-center gap-2">
            <div class="flex min-w-0 items-center gap-4">
                @if($siteSetting?->phone)
                    <a href="tel:{{ preg_replace('/\s+/', '', $siteSetting->phone) }}" class="hover:text-primary-yellow transition-colors flex items-center gap-2 whitespace-nowrap">
                        <svg class="w-3 h-3 text-primary-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $siteSetting->phone }}
                    </a>
                @endif
                @if($siteSetting?->email)
                    <a href="mailto:{{ $siteSetting->email }}" class="hidden sm:flex hover:text-primary-yellow transition-colors items-center gap-2">
                        <svg class="w-3 h-3 text-primary-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $siteSetting->email }}
                    </a>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <div class="hidden md:flex items-center gap-4">
                    @if($siteSetting?->facebook) <a href="{{ $siteSetting->facebook }}" target="_blank" class="hover:text-primary-yellow transition-colors"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg></a> @endif
                    @if($siteSetting?->instagram) <a href="{{ $siteSetting->instagram }}" target="_blank" class="hover:text-primary-yellow transition-colors"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a> @endif
                    @if($siteSetting?->linkedin) <a href="{{ $siteSetting->linkedin }}" target="_blank" class="hover:text-primary-yellow transition-colors"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"></path><circle cx="4" cy="4" r="2"></circle></svg></a> @endif
                </div>

                <div class="ml-0 flex items-center gap-2 border-l-0 pl-0 sm:ml-3 sm:pl-3 sm:border-l sm:border-slate-700">
                    @foreach($supportedLocales as $supportedLocale)
                        <a href="{{ $languageLinks[$supportedLocale] ?? ($supportedLocale === 'tr' ? '/' : '/'.$supportedLocale) }}"
                           class="{{ $locale === $supportedLocale ? 'text-primary-yellow font-bold' : 'text-slate-400 hover:text-white' }} transition-colors">
                            {{ strtoupper($supportedLocale) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur transition-shadow">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <a href="{{ $locale === 'tr' ? '/' : '/'.$locale }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo-new.webp') }}" alt="{{ $siteSetting?->{'company_name_'.$locale} }}" class="h-16 w-auto" width="162" height="192" decoding="async">
            </a>

            <nav class="hidden gap-8 text-[15px] font-medium lg:flex font-heading uppercase tracking-wide text-slate-800">
                <a href="{{ $locale === 'tr' ? '/hakkimizda' : '/'.$locale.'/about' }}" class="hover:text-primary-yellow transition-colors">{{ $menu['about'] }}</a>
                <a href="{{ $locale === 'tr' ? '/hizmetler' : '/'.$locale.'/services' }}" class="hover:text-primary-yellow transition-colors">{{ $menu['services'] }}</a>
                <a href="{{ $locale === 'tr' ? '/cozumler' : '/'.$locale.'/solutions' }}" class="hover:text-primary-yellow transition-colors">{{ $menu['solutions'] }}</a>
                <a href="{{ $locale === 'tr' ? '/urunler' : '/'.$locale.'/products' }}" class="hover:text-primary-yellow transition-colors">{{ $menu['products'] }}</a>
                <a href="{{ $locale === 'tr' ? '/referanslar' : '/'.$locale.'/references' }}" class="hover:text-primary-yellow transition-colors">{{ $menu['references'] }}</a>
                <a href="{{ $locale === 'tr' ? '/blog' : '/'.$locale.'/blog' }}" class="hover:text-primary-yellow transition-colors">{{ $menu['blog'] }}</a>
                <a href="{{ $locale === 'tr' ? '/iletisim' : '/'.$locale.'/contact' }}" class="hover:text-primary-yellow transition-colors">{{ $menu['contact'] }}</a>
            </nav>

            <div class="flex items-center gap-2">
                <x-button variant="primary" :href="$locale === 'tr' ? '/teklif-al' : '/'.$locale.'/get-quote'" data-track="cta_quote" class="hidden sm:inline-flex">
                    {{ __('site.cta_quote') }}
                </x-button>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="lg:hidden text-slate-800 hover:text-primary-yellow transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu" class="fixed inset-0 z-50 bg-dark-charcoal hidden opacity-0 transition-opacity duration-300">
        <div class="flex flex-col h-full">
            <!-- Mobile Menu Header -->
            <div class="flex items-center justify-between p-4 border-b border-slate-700">
                <img src="{{ asset('images/logo-new.webp') }}" alt="Lunar Ambalaj" class="h-12 w-auto" width="162" height="192" decoding="async">
                <button id="mobile-menu-close" class="text-white hover:text-primary-yellow transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu Content -->
            <nav class="flex-grow overflow-y-auto p-6">
                <div class="flex flex-col gap-4 text-lg font-heading uppercase text-white">
                    <a href="{{ $locale === 'tr' ? '/' : '/'.$locale }}" class="hover:text-primary-yellow transition-colors py-2 border-b border-slate-700">{{ $menu['home'] ?? __('site.menu.home') }}</a>
                    <a href="{{ $locale === 'tr' ? '/hakkimizda' : '/'.$locale.'/about' }}" class="hover:text-primary-yellow transition-colors py-2 border-b border-slate-700">{{ $menu['about'] }}</a>
                    <a href="{{ $locale === 'tr' ? '/hizmetler' : '/'.$locale.'/services' }}" class="hover:text-primary-yellow transition-colors py-2 border-b border-slate-700">{{ $menu['services'] }}</a>
                    <a href="{{ $locale === 'tr' ? '/cozumler' : '/'.$locale.'/solutions' }}" class="hover:text-primary-yellow transition-colors py-2 border-b border-slate-700">{{ $menu['solutions'] }}</a>
                    <a href="{{ $locale === 'tr' ? '/urunler' : '/'.$locale.'/products' }}" class="hover:text-primary-yellow transition-colors py-2 border-b border-slate-700">{{ $menu['products'] }}</a>
                    <a href="{{ $locale === 'tr' ? '/referanslar' : '/'.$locale.'/references' }}" class="hover:text-primary-yellow transition-colors py-2 border-b border-slate-700">{{ $menu['references'] }}</a>
                    <a href="{{ $locale === 'tr' ? '/blog' : '/'.$locale.'/blog' }}" class="hover:text-primary-yellow transition-colors py-2 border-b border-slate-700">{{ $menu['blog'] }}</a>
                    <a href="{{ $locale === 'tr' ? '/iletisim' : '/'.$locale.'/contact' }}" class="hover:text-primary-yellow transition-colors py-2 border-b border-slate-700">{{ $menu['contact'] }}</a>
                </div>

                <!-- Language Switcher -->
                <div class="mt-8 flex gap-4">
                    @foreach($supportedLocales as $supportedLocale)
                        <a href="{{ $languageLinks[$supportedLocale] ?? ($supportedLocale === 'tr' ? '/' : '/'.$supportedLocale) }}"
                           class="{{ $locale === $supportedLocale ? 'bg-primary-yellow text-dark-charcoal' : 'bg-slate-700 text-white' }} px-4 py-2 font-bold transition-colors">
                            {{ strtoupper($supportedLocale) }}
                        </a>
                    @endforeach
                </div>

                <!-- Mobile CTA -->
                <div class="mt-8">
                    <x-button variant="primary" :href="$locale === 'tr' ? '/teklif-al' : '/'.$locale.'/get-quote'" class="w-full text-center">
                        {{ __('site.cta_quote') }}
                    </x-button>
                </div>
            </nav>

            <!-- Mobile Footer -->
            <div class="p-6 border-t border-slate-700">
                <div class="flex gap-4 justify-center">
                    @if($siteSetting?->facebook) <a href="{{ $siteSetting->facebook }}" target="_blank" class="text-white hover:text-primary-yellow transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg></a> @endif
                    @if($siteSetting?->instagram) <a href="{{ $siteSetting->instagram }}" target="_blank" class="text-white hover:text-primary-yellow transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a> @endif
                    @if($siteSetting?->linkedin) <a href="{{ $siteSetting->linkedin }}" target="_blank" class="text-white hover:text-primary-yellow transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"></path><circle cx="4" cy="4" r="2"></circle></svg></a> @endif
                </div>
            </div>
        </div>
    </div>

    <main class="min-h-[65vh]">
        @if(session('success'))
            <div class="mx-auto mt-4 max-w-7xl rounded-md border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-800">{{ session('success') }}</div>
        @endif
        @if(session('warning'))
            <div class="mx-auto mt-4 max-w-7xl rounded-md border border-amber-300 bg-amber-50 px-4 py-3 text-amber-800">{{ session('warning') }}</div>
        @endif
        @if($errors->any())
            <div class="mx-auto mt-4 max-w-7xl rounded-md border border-rose-300 bg-rose-50 px-4 py-3 text-rose-800">{{ $errors->first() }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="mt-16 bg-dark-charcoal text-light-gray">
        <div class="mx-auto max-w-7xl px-4 py-16">
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4 mb-12">
                <!-- Column 1: About -->
                <div>
                    <img src="{{ asset('images/logo-new.webp') }}" alt="Lunar Ambalaj" class="h-12 w-auto mb-4 brightness-0 invert opacity-80" width="162" height="192" decoding="async">
                    <h4 class="mb-3 font-bold text-white font-heading uppercase text-sm tracking-wider">{{ app()->getLocale()==='tr' ? ($siteSetting?->company_name_tr ?: 'Lunar Ambalaj') : ($siteSetting?->company_name_en ?: 'Lunar Packaging') }}</h4>
                    @if(app()->getLocale()==='tr' && $siteSetting?->footer_short_tr)
                        <p class="mb-3 text-sm leading-relaxed">{{ $siteSetting->footer_short_tr }}</p>
                    @endif
                    @if(app()->getLocale()==='en' && $siteSetting?->footer_short_en)
                        <p class="mb-3 text-sm leading-relaxed">{{ $siteSetting->footer_short_en }}</p>
                    @endif
                    @if(!$siteSetting?->footer_short_tr && !$siteSetting?->footer_short_en)
                        <p class="mb-3 text-sm leading-relaxed">{{ __('site.footer.description') }}</p>
                    @endif
                </div>

                <!-- Column 2: Quick Links -->
                <div>
                    <h4 class="mb-4 font-bold text-white font-heading uppercase text-sm tracking-wider">{{ __('site.footer.quick_links') }}</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ $locale === 'tr' ? '/hakkimizda' : '/'.$locale.'/about' }}" class="hover:text-primary-yellow transition-colors">{{ $menu['about'] }}</a></li>
                        <li><a href="{{ $locale === 'tr' ? '/hizmetler' : '/'.$locale.'/services' }}" class="hover:text-primary-yellow transition-colors">{{ $menu['services'] }}</a></li>
                        <li><a href="{{ $locale === 'tr' ? '/urunler' : '/'.$locale.'/products' }}" class="hover:text-primary-yellow transition-colors">{{ $menu['products'] }}</a></li>
                        <li><a href="{{ $locale === 'tr' ? '/blog' : '/'.$locale.'/blog' }}" class="hover:text-primary-yellow transition-colors">{{ $menu['blog'] }}</a></li>
                        <li><a href="{{ $locale === 'tr' ? '/sss' : '/'.$locale.'/faq' }}" class="hover:text-primary-yellow transition-colors">{{ $menu['faq'] ?? __('site.menu.faq') }}</a></li>
                        <li><a href="{{ $locale === 'tr' ? '/iletisim' : '/'.$locale.'/contact' }}" class="hover:text-primary-yellow transition-colors">{{ $menu['contact'] }}</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact Info -->
                <div>
                    <h4 class="mb-4 font-bold text-white font-heading uppercase text-sm tracking-wider">{{ __('site.footer.contact_title') }}</h4>
                    <ul class="space-y-3 text-sm">
                        @if($siteSetting?->phone)
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-primary-yellow mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <a data-track="phone" href="tel:{{ preg_replace('/\s+/', '', $siteSetting->phone) }}" class="hover:text-primary-yellow transition-colors">{{ $siteSetting->phone }}</a>
                            </li>
                        @endif
                        @if($siteSetting?->email)
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-primary-yellow mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <a href="mailto:{{ $siteSetting->email }}" class="hover:text-primary-yellow transition-colors">{{ $siteSetting->email }}</a>
                            </li>
                        @endif
                        @if($siteSetting?->address)
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-primary-yellow mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ $siteSetting->address }}</span>
                            </li>
                        @endif
                        @if($siteSetting?->working_hours)
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-primary-yellow mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{!! nl2br(e($siteSetting->working_hours)) !!}</span>
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Column 4: Newsletter -->
                <div>
                    <h4 class="mb-4 font-bold text-white font-heading uppercase text-sm tracking-wider">{{ __('site.footer.newsletter') }}</h4>
                    <p class="text-sm mb-4">{{ __('site.footer.newsletter_desc') }}</p>
                    <form action="#" method="POST" class="flex gap-2">
                        <input type="email" placeholder="{{ __('site.footer.email_placeholder') }}" class="flex-grow px-3 py-2 bg-slate-800 border border-slate-700 text-white text-sm focus:outline-none focus:border-primary-yellow transition-colors" required>
                        <button type="submit" class="bg-primary-yellow text-dark-charcoal px-4 py-2 font-bold text-sm hover:bg-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </form>

                    <!-- Social Media -->
                    <div class="mt-6">
                        <div class="flex gap-3">
                            @if($siteSetting?->facebook)
                                <a href="{{ $siteSetting->facebook }}" target="_blank" class="w-10 h-10 bg-slate-800 hover:bg-primary-yellow text-white hover:text-dark-charcoal flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg>
                                </a>
                            @endif
                            @if($siteSetting?->instagram)
                                <a href="{{ $siteSetting->instagram }}" target="_blank" class="w-10 h-10 bg-slate-800 hover:bg-primary-yellow text-white hover:text-dark-charcoal flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                                </a>
                            @endif
                            @if($siteSetting?->linkedin)
                                <a href="{{ $siteSetting->linkedin }}" target="_blank" class="w-10 h-10 bg-slate-800 hover:bg-primary-yellow text-white hover:text-dark-charcoal flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"></path><circle cx="4" cy="4" r="2"></circle></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm">
                <p>&copy; {{ date('Y') }} {{ $siteSetting?->{'company_name_'.$locale} ?: 'Lunar Ambalaj' }}. {{ __('site.footer.rights_reserved') }}</p>
                <div class="flex flex-col gap-2 text-center sm:text-left sm:flex-row sm:flex-wrap sm:gap-6">
                    <a href="{{ $locale === 'tr' ? '/kvkk' : '/'.$locale.'/kvkk' }}" class="hover:text-primary-yellow transition-colors">{{ $legalLabels['kvkk'] }}</a>
                    <a href="{{ $locale === 'tr' ? '/gizlilik-politikasi' : '/'.$locale.'/privacy-policy' }}" class="hover:text-primary-yellow transition-colors">{{ $legalLabels['privacy'] }}</a>
                    <a href="{{ $locale === 'tr' ? '/cerez-politikasi' : '/'.$locale.'/cookie-policy' }}" class="hover:text-primary-yellow transition-colors">{{ $legalLabels['cookie'] }}</a>
                    <a href="{{ $locale === 'tr' ? '/kullanim-sartlari' : '/'.$locale.'/terms-of-use' }}" class="hover:text-primary-yellow transition-colors">{{ $legalLabels['terms'] }}</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float Button -->
    @if($siteSetting?->whatsapp)
        <a href="https://wa.me/{{ $siteSetting->whatsapp }}?text={{ urlencode(__('site.common.whatsapp_message')) }}"
           target="_blank"
           data-track="whatsapp"
           class="fixed bottom-8 right-8 z-30 bg-success-green text-white p-4 rounded-full shadow-lg hover:bg-success-green/90 hover:scale-110 transition-all duration-300 animate-pulse hover:animate-none"
           aria-label="WhatsApp">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
        </a>
    @endif

    <script>
        window.dataLayer = window.dataLayer || [];
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const currentPath = '{{ request()->getPathInfo() }}';

        function sendServerEvent(eventKey, payload = {}) {
            try {
                fetch('{{ route('track.event') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        event_key: eventKey,
                        page_path: currentPath,
                        payload: payload,
                    }),
                    keepalive: true,
                });
            } catch (e) {
                // silent fail for tracking transport
            }
        }

        @if(session('lead_submitted'))
            @php $leadPayload = session('lead_payload', []); @endphp
            window.dataLayer.push({
                event: 'lead_submit',
                lead_type: '{{ session('lead_type') }}',
                product_category: @json($leadPayload['product_category'] ?? null),
                quantity: @json($leadPayload['quantity'] ?? null),
                locale: '{{ app()->getLocale() }}',
                page_path: '{{ request()->getPathInfo() }}'
            });
            sendServerEvent('lead_submit', {
                lead_type: '{{ session('lead_type') }}',
                product_category: @json($leadPayload['product_category'] ?? null),
                quantity: @json($leadPayload['quantity'] ?? null),
                locale: '{{ app()->getLocale() }}'
            });
            @if($metaPixelId && session('lead_type') === 'quote')
                if (typeof fbq === 'function') { fbq('track', 'Lead'); }
            @endif
            @if($metaPixelId && session('lead_type') === 'contact')
                if (typeof fbq === 'function') { fbq('trackCustom', 'ContactFormSubmit'); }
            @endif
        @endif

        document.querySelectorAll('[data-track="phone"]').forEach(function (el) {
            el.addEventListener('click', function () {
                window.dataLayer.push({event: 'click_phone'});
                sendServerEvent('click_phone', { location: 'site' });
            });
        });

        document.querySelectorAll('[data-track="whatsapp"]').forEach(function (el) {
            el.addEventListener('click', function () {
                window.dataLayer.push({event: 'click_whatsapp'});
                sendServerEvent('click_whatsapp', { location: 'site' });
            });
        });

        document.querySelectorAll('[data-track="cta_quote"]').forEach(function (el) {
            el.addEventListener('click', function () {
                window.dataLayer.push({event: 'click_quote_cta', page_path: '{{ request()->getPathInfo() }}'});
                sendServerEvent('click_quote_cta', { page_path: currentPath });
            });
        });
    </script>
</body>
</html>
