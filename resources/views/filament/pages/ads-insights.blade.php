<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Filters</x-slot>
        <form wire:submit.prevent="applyFilters" class="grid gap-4 md:grid-cols-5">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">From</label>
                <input type="date" wire:model.defer="fromDate" class="w-full rounded-lg border-gray-300">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">To</label>
                <input type="date" wire:model.defer="toDate" class="w-full rounded-lg border-gray-300">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">Attribution</label>
                <select wire:model.defer="attributionModel" class="w-full rounded-lg border-gray-300">
                    <option value="last_touch">Last Touch</option>
                    <option value="first_touch">First Touch</option>
                </select>
            </div>
            <div class="flex items-end">
                <x-filament::button type="submit">Apply</x-filament::button>
            </div>
            <div class="flex items-end justify-start gap-2 md:justify-end">
                <x-filament::button color="warning" wire:click="syncNow">Sync Now</x-filament::button>
                <x-filament::button color="gray" wire:click="exportCsv">Export CSV</x-filament::button>
            </div>
        </form>
    </x-filament::section>

    <div class="grid gap-4 md:grid-cols-4">
        <x-filament::section>
            <x-slot name="heading">Leads (30d)</x-slot>
            <p class="text-3xl font-bold">{{ $this->summary['leads_total'] ?? 0 }}</p>
            <p class="text-sm text-gray-600">Quote: {{ $this->summary['leads_quote'] ?? 0 }} | Contact: {{ $this->summary['leads_contact'] ?? 0 }}</p>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Attributed Leads</x-slot>
            <p class="text-3xl font-bold">{{ $this->summary['attributed'] ?? 0 }}</p>
            <p class="text-sm text-gray-600">Rate: {{ $this->summary['attribution_rate'] ?? 0 }}%</p>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Spend (30d)</x-slot>
            <p class="text-3xl font-bold">{{ number_format((float) ($this->summary['spend_total'] ?? 0), 2) }}</p>
            <p class="text-sm text-gray-600">Campaign snapshots aggregate</p>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">CPL (30d)</x-slot>
            <p class="text-3xl font-bold">{{ number_format((float) ($this->summary['cpl'] ?? 0), 2) }}</p>
            <p class="text-sm text-gray-600">Period: {{ $this->summary['period'] ?? '-' }}</p>
            <p class="mt-2 text-xs text-gray-500">
                Phone: {{ $this->summary['click_phone'] ?? 0 }},
                WhatsApp: {{ $this->summary['click_whatsapp'] ?? 0 }},
                Quote CTA: {{ $this->summary['click_quote_cta'] ?? 0 }}
            </p>
        </x-filament::section>
    </div>

    <x-filament::section>
        <x-slot name="heading">Top Lead Sources ({{ $this->attributionModel === 'first_touch' ? 'First Touch' : 'Last Touch' }})</x-slot>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 pr-4">Source</th>
                        <th class="py-2 pr-4">Leads</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->sourceRows as $row)
                        <tr class="border-b last:border-0">
                            <td class="py-2 pr-4">{{ $row['source'] }}</td>
                            <td class="py-2 pr-4">{{ $row['leads'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-4 text-gray-500">No attribution data yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">UTM Hygiene</x-slot>
        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-lg border border-gray-200 p-3">
                <p class="text-xs text-gray-500">Total Logs</p>
                <p class="text-2xl font-bold">{{ $this->utmQuality['total_logs'] ?? 0 }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 p-3">
                <p class="text-xs text-gray-500">UTM Source Coverage</p>
                <p class="text-2xl font-bold">{{ number_format((float) ($this->utmQuality['coverage_rate'] ?? 0), 1) }}%</p>
            </div>
            <div class="rounded-lg border border-gray-200 p-3">
                <p class="text-xs text-gray-500">Tracked Rate</p>
                <p class="text-2xl font-bold">{{ number_format((float) ($this->utmQuality['tracked_rate'] ?? 0), 1) }}%</p>
            </div>
            <div class="rounded-lg border border-gray-200 p-3">
                <p class="text-xs text-gray-500">Quality Score</p>
                <p class="text-2xl font-bold">{{ number_format((float) ($this->utmQuality['quality_score'] ?? 0), 1) }}%</p>
            </div>
        </div>
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 pr-4">Metric</th>
                        <th class="py-2 pr-4">Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b"><td class="py-2 pr-4">Missing utm_source</td><td class="py-2 pr-4">{{ $this->utmQuality['missing_source'] ?? 0 }}</td></tr>
                    <tr class="border-b"><td class="py-2 pr-4">Invalid utm_source format</td><td class="py-2 pr-4">{{ $this->utmQuality['invalid_source'] ?? 0 }}</td></tr>
                    <tr class="border-b"><td class="py-2 pr-4">Missing utm_campaign (source present)</td><td class="py-2 pr-4">{{ $this->utmQuality['missing_campaign'] ?? 0 }}</td></tr>
                    <tr class="border-b"><td class="py-2 pr-4">gclid count</td><td class="py-2 pr-4">{{ $this->utmQuality['with_gclid'] ?? 0 }}</td></tr>
                    <tr><td class="py-2 pr-4">fbclid count</td><td class="py-2 pr-4">{{ $this->utmQuality['with_fbclid'] ?? 0 }}</td></tr>
                </tbody>
            </table>
        </div>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">Campaign Performance (30d)</x-slot>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 pr-4">Platform</th>
                        <th class="py-2 pr-4">Campaign</th>
                        <th class="py-2 pr-4">Spend</th>
                        <th class="py-2 pr-4">Impressions</th>
                        <th class="py-2 pr-4">Clicks</th>
                        <th class="py-2 pr-4">CTR %</th>
                        <th class="py-2 pr-4">CPC</th>
                        <th class="py-2 pr-4">Conversions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->campaignRows as $row)
                        <tr class="border-b last:border-0">
                            <td class="py-2 pr-4">{{ $row['platform'] }}</td>
                            <td class="py-2 pr-4">{{ $row['campaign_name'] }}</td>
                            <td class="py-2 pr-4">{{ number_format((float) $row['spend'], 2) }}</td>
                            <td class="py-2 pr-4">{{ number_format((int) $row['impressions']) }}</td>
                            <td class="py-2 pr-4">{{ number_format((int) $row['clicks']) }}</td>
                            <td class="py-2 pr-4">{{ number_format((float) $row['ctr'], 2) }}</td>
                            <td class="py-2 pr-4">{{ number_format((float) $row['cpc'], 2) }}</td>
                            <td class="py-2 pr-4">{{ number_format((int) $row['conversions']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-4 text-gray-500">No campaign snapshot data yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>

    <div class="grid gap-4 lg:grid-cols-2">
        <x-filament::section>
            <x-slot name="heading">Kategori Bazli Donusum (Quote)</x-slot>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2 pr-4">Kategori</th>
                            <th class="py-2 pr-4">Lead</th>
                            <th class="py-2 pr-4">Toplam Adet</th>
                            <th class="py-2 pr-4">Ort. Adet</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($this->categoryRows as $row)
                        <tr class="border-b last:border-0">
                            <td class="py-2 pr-4">{{ $row['category'] }}</td>
                            <td class="py-2 pr-4">{{ number_format((int) $row['lead_count']) }}</td>
                            <td class="py-2 pr-4">{{ number_format((int) $row['total_quantity']) }}</td>
                            <td class="py-2 pr-4">{{ number_format((int) $row['avg_quantity']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-gray-500">Kategori verisi bulunamadi.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Urun Bazli Donusum (Quote)</x-slot>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2 pr-4">Urun</th>
                            <th class="py-2 pr-4">Lead</th>
                            <th class="py-2 pr-4">Toplam Adet</th>
                            <th class="py-2 pr-4">Ort. Adet</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($this->productRows as $row)
                        <tr class="border-b last:border-0">
                            <td class="py-2 pr-4">{{ $row['product'] }}</td>
                            <td class="py-2 pr-4">{{ number_format((int) $row['lead_count']) }}</td>
                            <td class="py-2 pr-4">{{ number_format((int) $row['total_quantity']) }}</td>
                            <td class="py-2 pr-4">{{ number_format((int) $row['avg_quantity']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-gray-500">Urun verisi bulunamadi.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
