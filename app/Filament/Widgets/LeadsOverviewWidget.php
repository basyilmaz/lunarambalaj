<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class LeadsOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $todayStart = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();

        $today = Lead::query()->whereBetween('created_at', [$todayStart, now()])->count();
        $week = Lead::query()->whereBetween('created_at', [$weekStart, now()])->count();
        $month = Lead::query()->whereBetween('created_at', [$monthStart, now()])->count();
        $quote = Lead::query()->where('type', 'quote')->whereBetween('created_at', [$monthStart, now()])->count();
        $contact = Lead::query()->where('type', 'contact')->whereBetween('created_at', [$monthStart, now()])->count();

        return [
            Stat::make('Bugun lead', (string) $today)
                ->description('Anlik gunluk')
                ->color('primary'),
            Stat::make('Bu hafta lead', (string) $week)
                ->description('Pazartesi itibariyla')
                ->color('info'),
            Stat::make('Bu ay lead', (string) $month)
                ->description("Teklif: {$quote} | Iletisim: {$contact}")
                ->color('success'),
        ];
    }
}
