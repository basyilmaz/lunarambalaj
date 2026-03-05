<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Lead kaynaklari (son 30 gun)</x-slot>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 pr-4">Kaynak</th>
                        <th class="py-2 pr-4">Lead</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->getRows() as $row)
                        <tr class="border-b last:border-0">
                            <td class="py-2 pr-4">{{ $row['source'] }}</td>
                            <td class="py-2 pr-4">{{ $row['total'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-4 text-gray-500">Kayit bulunamadi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
