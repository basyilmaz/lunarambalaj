<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Lead pipeline (kanban)</x-slot>
        <p class="text-sm text-gray-600">
            Kartlari surukleyip ilgili kolona birakarak durum degistirebilirsiniz.
        </p>
    </x-filament::section>

    <div class="grid gap-4 xl:grid-cols-4 md:grid-cols-2 grid-cols-1">
        @foreach($this->statuses as $statusKey => $meta)
            <x-filament::section
                x-on:dragover.prevent
                x-on:drop.prevent="
                    const id = parseInt($event.dataTransfer.getData('leadId'));
                    if (! Number.isNaN(id)) { $wire.moveLead(id, '{{ $statusKey }}'); }
                "
            >
                <x-slot name="heading">{{ $meta['label'] }} ({{ count($this->columns[$statusKey] ?? []) }})</x-slot>
                <div class="space-y-3 min-h-[160px]">
                    @forelse($this->columns[$statusKey] ?? [] as $lead)
                        <article
                            class="rounded-lg border border-gray-200 bg-white p-3 shadow-sm cursor-move"
                            draggable="true"
                            x-on:dragstart="$event.dataTransfer.setData('leadId', '{{ $lead['id'] }}')"
                        >
                            <div class="flex items-start justify-between gap-2">
                                <div class="text-sm font-semibold text-gray-900">#{{ $lead['id'] }} - {{ $lead['name'] }}</div>
                                <span class="rounded bg-gray-100 px-2 py-0.5 text-xs text-gray-700">
                                    {{ $lead['type'] === 'quote' ? 'Teklif' : 'Iletisim' }}
                                </span>
                            </div>

                            @if(!empty($lead['company']))
                                <p class="mt-1 text-xs text-gray-700">{{ $lead['company'] }}</p>
                            @endif

                            <div class="mt-2 space-y-1 text-xs text-gray-600">
                                @if(!empty($lead['product_category']))
                                    <p>Kategori: {{ $lead['product_category'] }}</p>
                                @endif
                                @if(!empty($lead['quantity']))
                                    <p>Adet: {{ number_format((int) $lead['quantity']) }}</p>
                                @endif
                                @if(!empty($lead['phone']))
                                    <p>Tel: {{ $lead['phone'] }}</p>
                                @endif
                                @if(!empty($lead['email']))
                                    <p>{{ $lead['email'] }}</p>
                                @endif
                                @if(!empty($lead['assignee']))
                                    <p>Atanan: {{ $lead['assignee'] }}</p>
                                @endif
                                <p>Tarih: {{ $lead['created_at'] }}</p>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('filament.admin.resources.leads.edit', ['record' => $lead['id']]) }}"
                                   class="text-xs text-primary-600 hover:underline">
                                    Detayi ac
                                </a>
                            </div>
                        </article>
                    @empty
                        <p class="text-xs text-gray-500">Bu kolonda kayit yok.</p>
                    @endforelse
                </div>
            </x-filament::section>
        @endforeach
    </div>
</x-filament-panels::page>
