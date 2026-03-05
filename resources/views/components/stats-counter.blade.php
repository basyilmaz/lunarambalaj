@props(['stats' => []])

<section class="bg-dark-charcoal py-20" data-aos="fade-up">
    <div class="mx-auto max-w-7xl px-4">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($stats as $stat)
                <div class="text-center">
                    <div class="flex items-center justify-center gap-2 mb-2">
                        <span class="stat-counter text-5xl md:text-6xl font-bold text-primary-yellow font-heading" data-target="{{ $stat['number'] ?? 0 }}">
                            0
                        </span>
                        @if(isset($stat['suffix']))
                            <span class="text-4xl md:text-5xl font-bold text-primary-yellow font-heading">{{ $stat['suffix'] }}</span>
                        @endif
                    </div>
                    <p class="text-light-gray text-sm uppercase tracking-wider">{{ $stat['label'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
