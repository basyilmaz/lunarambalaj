@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="relative bg-dark-charcoal min-h-[450px] flex items-center overflow-hidden">
    <div class="absolute inset-0">
        <img
            src="{{ asset('images/hero-bg.png') }}"
            alt="References Background"
            class="w-full h-full object-cover opacity-30 grayscale blur-[2px]"
            width="1920"
            height="1080"
            loading="eager"
            fetchpriority="high"
            decoding="async"
        >
        <div class="absolute inset-0 bg-gradient-to-r from-dark-charcoal via-dark-charcoal/80 to-transparent"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 py-20 w-full">
        <div class="inline-block mb-4 px-4 py-1.5 border-l-4 border-primary-yellow bg-white/10 backdrop-blur text-white text-xs font-bold uppercase tracking-[0.2em]">
            {{ app()->getLocale() === 'tr' ? 'Etik ve Güven' : 'Ethics & Trust' }}
        </div>

        <h1 class="text-4xl md:text-5xl font-bold text-white font-heading leading-tight mb-6 uppercase">
            {{ app()->getLocale() === 'tr' ? 'Çözüm Ortaklarımız' : 'Our Solution Partners' }}
        </h1>

        <p class="text-xl text-slate-300 max-w-3xl font-light leading-relaxed">
            @if(app()->getLocale() === 'tr')
                <b>HORECA (Otel, Restoran, Kafe)</b>, perakende zincirleri ve kurumsal markalara özel baskılı ambalaj tedariki sağlıyoruz. 
                Sektörel güven ilkesi ve veri gizliliği standartlarımız gereği, çalıştığımız kurumların ticari isimlerini web üzerinde listelemiyoruz.
            @else
                We supply custom printed packaging to <b>HORECA (Hotel, Restaurant, Cafe)</b>, retail chains and corporate brands. 
                Due to our sectoral trust principles and data privacy standards (KVKK/GDPR), we do not publicly list our commercial partners' names.
            @endif
        </p>
    </div>
</section>

<!-- KVKK & Confidentiality Notice -->
<section class="py-16 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4">
        <div class="bg-slate-50 border-l-8 border-primary-yellow p-8 md:p-12 shadow-sm rounded-r-lg flex flex-col md:flex-row gap-8 items-center">
            <div class="w-20 h-20 shrink-0 bg-white rounded-full shadow-md flex items-center justify-center text-primary-yellow">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold font-heading text-slate-900 mb-3 uppercase">
                    {{ app()->getLocale() === 'tr' ? 'Gizlilik ve KVKK Politikamız' : 'Privacy & Data Protection Policy' }}
                </h2>
                <p class="text-slate-600 leading-relaxed text-sm md:text-base">
                    @if(app()->getLocale() === 'tr')
                        Müşterilerimizin tescilli markalarının (logolarının ve özel baskı tasarımlarının) güvenliği bizim için en üst düzey önceliktir.
                        KVKK (Kişisel Verilerin Korunması Kanunu) ve ticari sözleşmelerimizdeki gizlilik taahhütlerine saygı duyarak, açık rıza olmaksızın portföyümüzde bulunan hiçbir markayı "Örnek Müşterilerimiz" altında paylaşmıyoruz. 
                        Size özel sunumlarımızda, maskelenmiş sektörel örnekler ve numune çalışmaları sadece fiziki veya birebir dijital toplantılarda iletilmektedir.
                    @else
                        The security of our clients' registered trademarks (logos and custom print designs) is our top priority.
                        Respecting privacy laws (GDPR/KVKK) and the confidentiality clauses in our commercial agreements, we do not share any brands from our portfolio under "Our Clients" without explicit consent. 
                        Rest assured, masked sectoral examples and physical sample cases are provided exclusively during face-to-face or direct digital B2B meetings.
                    @endif
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Sectors Served Grid -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-primary-yellow font-bold tracking-widest uppercase text-sm mb-2 block">
                {{ app()->getLocale() === 'tr' ? 'Kimlere Üretiyoruz?' : 'Who Do We Manufacture For?' }}
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 font-heading uppercase mb-4">
                {{ app()->getLocale() === 'tr' ? 'Hizmet Verdiğimiz Sektörler' : 'Sectors We Serve' }}
            </h2>
        </div>

        @php
            if (app()->getLocale() === 'tr') {
                $segments = [
                    ['image' => 'images/ref-horeca.jpg', 'title' => 'Kahve Zincirleri ve Kafeler', 'desc' => 'Uluslararası ve yerel kahve zincirleri için kendi logolarında basılı kağıt/PET bardak, pipet, stick şeker ve peçete grubunda milyonluk adetlerde üretim yapıyoruz.'],
                    ['image' => 'images/ref-hotel.jpg', 'title' => 'Otel ve Konaklama Tesisleri', 'desc' => 'Premium oteller için kaliteden ödün vermeden markalı ıslak mendil, bayraklı restoran kürdanı ve oda ikramı tekli pipet çözümleri sunuyoruz.'],
                    ['image' => 'images/ref-retail.jpg', 'title' => 'Perakende ve Süpermarket', 'desc' => 'Süpermarket Private Label (Özel Marka) reyonları için Toptan paketlenmiş baskısız ev/party tipi sarf tüketim ürünleri tedariki.'],
                    ['image' => 'images/ref-fastfood.jpg', 'title' => 'Fast-Food ve Yemek Zincirleri', 'desc' => 'Hızlı yemek zincirlerinin spesifik ölçü gereksinimlerine uyan planlı teslimat ağıyla kesintisiz operasyonel süreç yönetimi.'],
                    ['image' => 'images/ref-catering.jpg', 'title' => 'Catering & Organizasyon', 'desc' => 'Havayolları, kurumsal yemek firmaları ve lansman etkinlikleri için kısa süre terminli çok çeşitli paket çözümleri.'],
                    ['image' => 'images/ref-wholesale.jpg', 'title' => 'Ambalaj Toptancıları', 'desc' => 'Yerel pazarda dağıtım yapan ambalaj toptancılarına fason baskısız olarak standart kolileme ile hızlı sevk depoları.']
                ];
            } else {
                $segments = [
                    ['image' => 'images/ref-horeca.jpg', 'title' => 'Coffee Chains & Cafes', 'desc' => 'We manufacture millions of custom logo printed paper/PET cups, straws, sugar sticks and napkins for international/local coffee chains.'],
                    ['image' => 'images/ref-hotel.jpg', 'title' => 'Hotel & Hospitality', 'desc' => 'We offer clear solutions like branded single sachet wet wipes, flag toothpicks and wrapped straws for premium hotels without sacrificing quality.'],
                    ['image' => 'images/ref-retail.jpg', 'title' => 'Retail & Supermarkets', 'desc' => 'Supplying wholesale packed, unprinted household or party consumables for supermarket Private Label shelf spaces.'],
                    ['image' => 'images/ref-fastfood.jpg', 'title' => 'Fast-Food & Restaurants', 'desc' => 'Uninterrupted operational management with a planned delivery network adapted to the specific dimensions of fast food chains.'],
                    ['image' => 'images/ref-catering.jpg', 'title' => 'Catering & Organizations', 'desc' => 'Short lead-time diverse packaging solutions requested by airline caterings, corporate food firms, and event launch planners.'],
                    ['image' => 'images/ref-wholesale.jpg', 'title' => 'Packaging Wholesalers', 'desc' => 'We supply contract-manufactured unprinted goods with standard cartoning for local market packaging product distributors.']
                ];
            }
        @endphp

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($segments as $idx => $segment)
                <div class="group bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden flex flex-col hover:shadow-2xl hover:border-primary-yellow transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $idx * 100 }}">
                    <div class="h-48 relative overflow-hidden bg-slate-100">
                        <img
                            src="{{ asset($segment['image']) }}"
                            alt="{{ $segment['title'] }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            width="768"
                            height="384"
                            loading="lazy"
                            decoding="async"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                    </div>
                    <div class="p-8 flex flex-col flex-1 relative">
                        <!-- Connecting Line Decoration -->
                        <div class="absolute -top-4 w-12 h-1 bg-primary-yellow hidden group-hover:block transition-all"></div>
                        <h3 class="text-xl font-bold font-heading text-slate-900 mb-3 uppercase tracking-wide group-hover:text-primary-yellow transition-colors">
                            {{ $segment['title'] }}
                        </h3>
                        <p class="text-sm text-slate-600 leading-relaxed flex-1">
                            {{ $segment['desc'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 bg-primary-yellow">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold font-heading text-dark-charcoal uppercase mb-4">
            {{ app()->getLocale() === 'tr' ? 'Örnek Çalışmaları Görmek İster Misiniz?' : 'Would You Like to See Samples?' }}
        </h2>
        <p class="mb-8 text-dark-charcoal max-w-2xl mx-auto opacity-90">
            {{ app()->getLocale() === 'tr' ? 'Sizin sektörünüze ait logolu/logosuz fiziki numunelerle projenizi konuşalım. Bize talebinizi iletin.' : 'Let’s discuss your project with physical printed/unprinted samples related to your industry. Send us your inquiry.' }}
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route(app()->getLocale() . '.quote') }}" class="px-8 py-3 bg-dark-charcoal text-white font-bold uppercase tracking-wider text-sm hover:bg-slate-800 transition-colors shadow-lg">
                {{ app()->getLocale() === 'tr' ? 'Fiyat Teklifi Al' : 'Request Quotation' }}
            </a>
            <a href="{{ route(app()->getLocale() . '.contact') }}" class="px-8 py-3 bg-white text-dark-charcoal font-bold uppercase tracking-wider text-sm hover:bg-slate-100 transition-colors shadow-lg">
                {{ app()->getLocale() === 'tr' ? 'İletişim Kur' : 'Contact Us' }}
            </a>
        </div>
    </div>
</section>

@endsection
