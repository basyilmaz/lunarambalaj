<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Kapsama ozeti</x-slot>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 pr-4">Modul</th>
                        <th class="py-2 pr-4">Toplam</th>
                        <th class="py-2 pr-4">Tam</th>
                        <th class="py-2 pr-4">Eksik</th>
                        <th class="py-2 pr-4">Oran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->summaryRows as $row)
                        <tr class="border-b last:border-0">
                            <td class="py-2 pr-4">{{ $row['module'] }}</td>
                            <td class="py-2 pr-4">{{ $row['total'] }}</td>
                            <td class="py-2 pr-4">{{ $row['full'] }}</td>
                            <td class="py-2 pr-4">{{ $row['missing'] }}</td>
                            <td class="py-2 pr-4">{{ $row['percent'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">Eksik ceviri kayitlari</x-slot>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 pr-4">Modul</th>
                        <th class="py-2 pr-4">Kayit</th>
                        <th class="py-2 pr-4">Baslik</th>
                        <th class="py-2 pr-4">Eksik diller</th>
                        <th class="py-2 pr-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->missingRows as $row)
                        <tr class="border-b last:border-0">
                            <td class="py-2 pr-4">{{ $row['module'] }}</td>
                            <td class="py-2 pr-4">#{{ $row['record_id'] }}</td>
                            <td class="py-2 pr-4">{{ $row['title'] }}</td>
                            <td class="py-2 pr-4">{{ implode(', ', $row['missing']) }}</td>
                            <td class="py-2 pr-4">
                                <a href="{{ $row['edit_url'] }}" class="text-primary-600 hover:underline">Kaydi ac</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-gray-500">Eksik ceviri bulunamadi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>
