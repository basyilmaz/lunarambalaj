<?php

namespace App\Filament\Widgets;

use App\Models\AttributionLog;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class LeadSourcesWidget extends Widget
{
    protected static string $view = 'filament.widgets.lead-sources-widget';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<int, array{source:string,total:int}>
     */
    public function getRows(): array
    {
        $from = Carbon::now()->subDays(30)->startOfDay();
        $to = Carbon::now()->endOfDay();

        return AttributionLog::query()
            ->selectRaw("COALESCE(utm_source, 'direct') as source, COUNT(*) as total")
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('source')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn ($row): array => [
                'source' => (string) $row->source,
                'total' => (int) $row->total,
            ])
            ->all();
    }
}
