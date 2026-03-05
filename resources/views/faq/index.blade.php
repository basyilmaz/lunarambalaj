@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<x-hero
    :subtitle="app()->getLocale() === 'tr' ? 'Sıkça Sorulan Sorular' : 'Frequently Asked Questions'"
    :title="app()->getLocale() === 'tr' ? 'Sipariş Sürecinden Teslimata Tüm Detaylar' : 'All Details from Order to Delivery'"
    height="min-h-[400px]"
>
    <p class="text-xl text-slate-300 mb-10 max-w-2xl font-light leading-relaxed">
        {{ app()->getLocale() === 'tr' ? 'Teklif, minimum sipariş, baskı onayı, paketleme ve sevkiyat sürecine dair sık sorulan soruları burada bulabilirsiniz.' : 'Find common questions about quotation, MOQ, print approval, packaging and shipment process.' }}
    </p>
</x-hero>

<!-- FAQ Categories -->
<section class="bg-slate-50 py-12">
    <div class="mx-auto max-w-7xl px-4">
        <div class="grid gap-6 md:grid-cols-3">
            <div class="bg-white p-6 border-l-4 border-primary-yellow" data-aos="fade-up">
                <svg class="w-10 h-10 text-primary-yellow mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="font-bold text-slate-900 uppercase tracking-wide text-sm">
                    {{ app()->getLocale() === 'tr' ? 'MOQ ve Fiyatlama' : 'MOQ and Pricing' }}
                </p>
            </div>
            <div class="bg-white p-6 border-l-4 border-info-blue" data-aos="fade-up" data-aos-delay="100">
                <svg class="w-10 h-10 text-info-blue mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                </svg>
                <p class="font-bold text-slate-900 uppercase tracking-wide text-sm">
                    {{ app()->getLocale() === 'tr' ? 'Baskı ve Tasarım Dosyaları' : 'Print and Artwork Files' }}
                </p>
            </div>
            <div class="bg-white p-6 border-l-4 border-success-green" data-aos="fade-up" data-aos-delay="200">
                <svg class="w-10 h-10 text-success-green mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
                <p class="font-bold text-slate-900 uppercase tracking-wide text-sm">
                    {{ app()->getLocale() === 'tr' ? 'Termin ve Sevkiyat' : 'Lead Time and Shipment' }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Accordion -->
<section class="py-16 bg-white">
    <div class="mx-auto max-w-4xl px-4">
        @if($faqs->count() > 0)
            <div class="space-y-4">
                @foreach($faqs as $faq)
                    @php $t = $faq->translation(app()->getLocale()); @endphp
                    @if($t)
                        <div class="faq-item bg-white border border-slate-200 rounded-lg overflow-hidden hover:border-primary-yellow transition-colors" data-aos="fade-up">
                            <button class="faq-question w-full text-left p-6 flex items-center justify-between gap-4 group">
                                <span class="font-bold text-slate-900 text-lg group-hover:text-primary-yellow transition-colors">
                                    {{ $t->question }}
                                </span>
                                <svg class="faq-icon w-6 h-6 text-slate-400 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="faq-answer hidden">
                                <div class="px-6 pb-6 text-slate-600 leading-relaxed">
                                    {{ $t->answer }}
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-lg text-slate-600">
                    {{ app()->getLocale() === 'tr' ? 'Henüz soru bulunamadı.' : 'No questions found yet.' }}
                </p>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-amber-50">
    <div class="mx-auto max-w-7xl px-4">
        <div class="bg-white p-8 border-l-4 border-primary-yellow">
            <div class="flex items-start gap-6">
                <div class="flex-shrink-0">
                    <svg class="w-12 h-12 text-primary-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 font-heading uppercase mb-3">
                        {{ app()->getLocale() === 'tr' ? 'Aradığınız Yanıtı Bulamadınız mı?' : 'Could Not Find Your Answer?' }}
                    </h2>
                    <p class="text-slate-700 leading-relaxed mb-4">
                        {{ app()->getLocale() === 'tr' ? 'Ürün kategorisi, adet ve baskı detaylarıyla teklif formu gönderin; ekibimiz en uygun ürün setini önersin. 24 saat içinde size geri dönüş yapıyoruz.' : 'Send your quote request with product category, quantity and printing details; our team will suggest the best-fit product bundle. We respond within 24 hours.' }}
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <x-button variant="primary" :href="route(app()->getLocale() . '.quote')">
                            {{ app()->getLocale() === 'tr' ? '24 Saatte Teklif Alın' : 'Get Quote Within 24 Hours' }}
                        </x-button>
                        <x-button variant="outline" :href="route(app()->getLocale() . '.contact')">
                            {{ app()->getLocale() === 'tr' ? 'Bize Ulaşın' : 'Contact Us' }}
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Accordion Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        const icon = item.querySelector('.faq-icon');

        question.addEventListener('click', () => {
            // Close all other FAQs
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.querySelector('.faq-answer').classList.add('hidden');
                    otherItem.querySelector('.faq-icon').style.transform = 'rotate(0deg)';
                }
            });

            // Toggle current FAQ
            answer.classList.toggle('hidden');
            icon.style.transform = answer.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        });
    });
});
</script>

@endsection
