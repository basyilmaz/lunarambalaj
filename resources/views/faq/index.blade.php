@extends('layouts.app')

@section('content')
@php
    $locale = app()->getLocale();
    $copy = [
        'tr' => [
            'subtitle' => 'Sıkça Sorulan Sorular',
            'title' => 'Sipariş Sürecinden Teslimata Tüm Detaylar',
            'desc' => 'Teklif, minimum sipariş, baskı onayı, paketleme ve sevkiyat sürecine dair sık sorulan soruları burada bulabilirsiniz.',
            'cat1' => 'MOQ ve Fiyatlama',
            'cat2' => 'Baskı ve Tasarım Dosyaları',
            'cat3' => 'Termin ve Sevkiyat',
            'empty' => 'Henüz soru bulunamadı.',
            'ctaTitle' => 'Aradığınız Yanıtı Bulamadınız mı?',
            'ctaDesc' => 'Ürün kategorisi, adet ve baskı detaylarıyla teklif formu gönderin; ekibimiz en uygun ürün setini önersin. 24 saat içinde size geri dönüş yapıyoruz.',
            'ctaQuote' => '24 Saatte Teklif Alın',
            'ctaContact' => 'Bize Ulaşın',
        ],
        'en' => [
            'subtitle' => 'Frequently Asked Questions',
            'title' => 'All Details from Order to Delivery',
            'desc' => 'Find common questions about quotation, MOQ, print approval, packaging and shipment process.',
            'cat1' => 'MOQ and Pricing',
            'cat2' => 'Print and Artwork Files',
            'cat3' => 'Lead Time and Shipment',
            'empty' => 'No questions found yet.',
            'ctaTitle' => 'Could Not Find Your Answer?',
            'ctaDesc' => 'Send your quote request with product category, quantity and printing details; our team will suggest the best-fit product bundle. We respond within 24 hours.',
            'ctaQuote' => 'Get Quote Within 24 Hours',
            'ctaContact' => 'Contact Us',
        ],
        'ru' => [
            'subtitle' => 'Часто задаваемые вопросы',
            'title' => 'Все детали от заказа до поставки',
            'desc' => 'Здесь собраны частые вопросы о расчете, минимальном заказе, утверждении печати, упаковке и отгрузке.',
            'cat1' => 'MOQ и ценообразование',
            'cat2' => 'Печать и файлы дизайна',
            'cat3' => 'Сроки и отгрузка',
            'empty' => 'Пока нет вопросов.',
            'ctaTitle' => 'Не нашли нужный ответ?',
            'ctaDesc' => 'Отправьте запрос с категорией, объемом и деталями печати; команда предложит оптимальный набор и вернется в течение 24 часов.',
            'ctaQuote' => 'Получить расчет за 24 часа',
            'ctaContact' => 'Связаться с нами',
        ],
        'ar' => [
            'subtitle' => 'الأسئلة الشائعة',
            'title' => 'كل التفاصيل من الطلب حتى التسليم',
            'desc' => 'ستجد هنا أكثر الأسئلة شيوعًا حول التسعير والحد الأدنى والموافقة على الطباعة والتغليف والشحن.',
            'cat1' => 'MOQ والتسعير',
            'cat2' => 'ملفات الطباعة والتصميم',
            'cat3' => 'المدة والشحن',
            'empty' => 'لا توجد أسئلة حتى الآن.',
            'ctaTitle' => 'لم تجد الإجابة التي تبحث عنها؟',
            'ctaDesc' => 'أرسل طلب عرض السعر مع الفئة والكمية وتفاصيل الطباعة، وسيقترح فريقنا الحل المناسب خلال 24 ساعة.',
            'ctaQuote' => 'احصل على عرض خلال 24 ساعة',
            'ctaContact' => 'تواصل معنا',
        ],
        'es' => [
            'subtitle' => 'Preguntas Frecuentes',
            'title' => 'Todos los Detalles del Pedido a la Entrega',
            'desc' => 'Aquí encontrarás preguntas comunes sobre cotización, pedido mínimo, aprobación de impresión, empaque y envío.',
            'cat1' => 'MOQ y Precios',
            'cat2' => 'Impresión y Archivos de Diseño',
            'cat3' => 'Plazo y Envío',
            'empty' => 'Aún no hay preguntas publicadas.',
            'ctaTitle' => '¿No Encontraste tu Respuesta?',
            'ctaDesc' => 'Envía tu solicitud con categoría, cantidad y detalles de impresión; nuestro equipo propondrá el set ideal en 24 horas.',
            'ctaQuote' => 'Recibir Cotización en 24 Horas',
            'ctaContact' => 'Contáctanos',
        ],
    ];
    $t = $copy[$locale] ?? $copy['en'];
@endphp

<x-hero
    :subtitle="$t['subtitle']"
    :title="$t['title']"
    height="min-h-[400px]"
>
    <p class="mb-10 max-w-2xl text-xl font-light leading-relaxed text-slate-300">
        {{ $t['desc'] }}
    </p>
</x-hero>

<section class="bg-slate-50 py-12">
    <div class="mx-auto max-w-7xl px-4">
        <div class="grid gap-6 md:grid-cols-3">
            <div class="border-l-4 border-primary-yellow bg-white p-6" data-aos="fade-up">
                <svg class="mb-3 h-10 w-10 text-primary-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-bold uppercase tracking-wide text-slate-900">{{ $t['cat1'] }}</p>
            </div>
            <div class="border-l-4 border-info-blue bg-white p-6" data-aos="fade-up" data-aos-delay="100">
                <svg class="mb-3 h-10 w-10 text-info-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                </svg>
                <p class="text-sm font-bold uppercase tracking-wide text-slate-900">{{ $t['cat2'] }}</p>
            </div>
            <div class="border-l-4 border-success-green bg-white p-6" data-aos="fade-up" data-aos-delay="200">
                <svg class="mb-3 h-10 w-10 text-success-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
                <p class="text-sm font-bold uppercase tracking-wide text-slate-900">{{ $t['cat3'] }}</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-16">
    <div class="mx-auto max-w-4xl px-4">
        @if($faqs->count() > 0)
            <div class="space-y-4">
                @foreach($faqs as $faq)
                    @php $faqTranslation = $faq->translation(app()->getLocale()); @endphp
                    @if($faqTranslation)
                        <div class="faq-item overflow-hidden rounded-lg border border-slate-200 bg-white transition-colors hover:border-primary-yellow" data-aos="fade-up">
                            <button class="faq-question group flex w-full items-center justify-between gap-4 p-6 text-left">
                                <span class="text-lg font-bold text-slate-900 transition-colors group-hover:text-primary-yellow">
                                    {{ $faqTranslation->question }}
                                </span>
                                <svg class="faq-icon h-6 w-6 shrink-0 text-slate-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="faq-answer hidden">
                                <div class="px-6 pb-6 leading-relaxed text-slate-600">
                                    {{ $faqTranslation->answer }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="py-16 text-center">
                <svg class="mx-auto mb-4 h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-lg text-slate-600">{{ $t['empty'] }}</p>
            </div>
        @endif
    </div>
</section>

<section class="bg-amber-50 py-16">
    <div class="mx-auto max-w-7xl px-4">
        <div class="border-l-4 border-primary-yellow bg-white p-8">
            <div class="flex items-start gap-6">
                <div class="shrink-0">
                    <svg class="h-12 w-12 text-primary-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="mb-3 font-heading text-2xl font-bold uppercase text-slate-900">{{ $t['ctaTitle'] }}</h2>
                    <p class="mb-4 leading-relaxed text-slate-700">{{ $t['ctaDesc'] }}</p>
                    <div class="flex flex-wrap gap-4">
                        <x-button variant="primary" :href="route(app()->getLocale() . '.quote')">{{ $t['ctaQuote'] }}</x-button>
                        <x-button variant="outline" :href="route(app()->getLocale() . '.contact')">{{ $t['ctaContact'] }}</x-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach((item) => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        const icon = item.querySelector('.faq-icon');

        question.addEventListener('click', () => {
            faqItems.forEach((otherItem) => {
                if (otherItem === item) {
                    return;
                }
                otherItem.querySelector('.faq-answer').classList.add('hidden');
                otherItem.querySelector('.faq-icon').style.transform = 'rotate(0deg)';
            });

            answer.classList.toggle('hidden');
            icon.style.transform = answer.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        });
    });
});
</script>

@endsection
