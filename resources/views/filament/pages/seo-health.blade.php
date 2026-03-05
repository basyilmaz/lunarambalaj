<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-4">
        <x-filament::section>
            <x-slot name="heading">Toplam kayit</x-slot>
            <p class="text-3xl font-bold">{{ $this->summary['total'] ?? 0 }}</p>
        </x-filament::section>
        <x-filament::section>
            <x-slot name="heading">Tamamlanan</x-slot>
            <p class="text-3xl font-bold">{{ $this->summary['complete'] ?? 0 }}</p>
        </x-filament::section>
        <x-filament::section>
            <x-slot name="heading">Eksik</x-slot>
            <p class="text-3xl font-bold">{{ $this->summary['missing'] ?? 0 }}</p>
        </x-filament::section>
        <x-filament::section>
            <x-slot name="heading">Tamamlanma</x-slot>
            <p class="text-3xl font-bold">{{ $this->summary['percent'] ?? 0 }}%</p>
        </x-filament::section>
    </div>

    <x-filament::section>
        <x-slot name="heading">SEO detay listesi</x-slot>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 pr-4">Tip</th>
                        <th class="py-2 pr-4">Dil</th>
                        <th class="py-2 pr-4">Baslik</th>
                        <th class="py-2 pr-4">Slug</th>
                        <th class="py-2 pr-4">Title len</th>
                        <th class="py-2 pr-4">Desc len</th>
                        <th class="py-2 pr-4">Sorunlar</th>
                        <th class="py-2 pr-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->rows as $row)
                        <tr class="border-b last:border-0">
                            <td class="py-2 pr-4">{{ $row['type'] }}</td>
                            <td class="py-2 pr-4">{{ $row['lang'] }}</td>
                            <td class="py-2 pr-4">{{ $row['title'] }}</td>
                            <td class="py-2 pr-4">{{ $row['slug'] }}</td>
                            <td class="py-2 pr-4">{{ $row['seo_title_len'] }}</td>
                            <td class="py-2 pr-4">{{ $row['seo_desc_len'] }}</td>
                            <td class="py-2 pr-4">
                                @if(count($row['issues']) === 0)
                                    <span class="text-green-600">Tam</span>
                                @else
                                    <span class="text-red-600">{{ implode(', ', $row['issues']) }}</span>
                                @endif
                            </td>
                            <td class="py-2 pr-4">
                                <a href="{{ $row['edit_url'] }}" class="text-primary-600 hover:underline">Duzenle</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-4 text-gray-500">Kayit bulunamadi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>
