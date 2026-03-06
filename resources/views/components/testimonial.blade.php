@props(['testimonial'])

<div class="bg-white p-8 shadow-sm border-l-4 border-primary-yellow h-full flex flex-col">
    <div class="flex items-center gap-1 mb-4">
        @for($i = 0; $i < ($testimonial['rating'] ?? 5); $i++)
            <svg class="w-5 h-5 text-primary-yellow fill-current" viewBox="0 0 20 20">
                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
            </svg>
        @endfor
    </div>

    <blockquote class="text-text-gray leading-relaxed mb-6 flex-grow italic">
        "{{ $testimonial['content'] ?? $testimonial['tr'] ?? '' }}"
    </blockquote>

    <div class="flex items-center gap-4 border-t border-slate-200 pt-4">
        @if(isset($testimonial['company_logo']) && $testimonial['company_logo'])
            <img
                src="{{ asset($testimonial['company_logo']) }}"
                alt="{{ $testimonial['company_name'] ?? '' }}"
                class="w-12 h-12 object-contain"
                width="48"
                height="48"
                decoding="async"
            >
        @else
            <div class="w-12 h-12 rounded-full bg-primary-yellow flex items-center justify-center">
                <span class="text-dark-charcoal font-bold text-lg">{{ substr($testimonial['author_name'] ?? 'U', 0, 1) }}</span>
            </div>
        @endif

        <div>
            <p class="font-bold text-slate-900">{{ $testimonial['author_name'] ?? '' }}</p>
            <p class="text-sm text-text-gray">{{ $testimonial['author_position'] ?? '' }}</p>
            <p class="text-sm text-primary-yellow font-medium">{{ $testimonial['company_name'] ?? '' }}</p>
        </div>
    </div>
</div>
