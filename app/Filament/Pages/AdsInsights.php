<?php

namespace App\Filament\Pages;

use App\Models\AttributionLog;
use App\Models\CampaignSnapshot;
use App\Models\EventLog;
use App\Models\Lead;
use App\Models\ReportCache;
use App\Services\Ads\AdsSyncService;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdsInsights extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationGroup = 'Analiz ve Reklam';

    protected static ?string $navigationLabel = 'Ads Insights';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.ads-insights';

    public string $fromDate = '';

    public string $toDate = '';

    public string $attributionModel = 'last_touch';

    public array $summary = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $sourceRows = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $campaignRows = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $categoryRows = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $productRows = [];

    /**
     * @var array<string, int|float>
     */
    public array $utmQuality = [];

    public function mount(): void
    {
        $this->fromDate = now()->subDays(30)->toDateString();
        $this->toDate = now()->toDateString();
        $this->attributionModel = 'last_touch';
        $this->reloadData();
    }

    public function applyFilters(): void
    {
        $this->reloadData();
    }

    public function syncNow(): void
    {
        [$from, $to] = $this->resolveRange();
        $service = app(AdsSyncService::class);
        foreach (['google_ads', 'meta_ads'] as $platform) {
            $service->syncPlatform($platform, $from->toImmutable(), $to->toImmutable());
        }

        $this->reloadData();
    }

    public function exportCsv(): StreamedResponse
    {
        $rows = $this->campaignRows;
        $filename = 'ads-insights-' . $this->fromDate . '-to-' . $this->toDate . '.csv';

        return response()->streamDownload(function () use ($rows): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['platform', 'campaign_name', 'spend', 'impressions', 'clicks', 'ctr', 'cpc', 'conversions']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row['platform'],
                    $row['campaign_name'],
                    $row['spend'],
                    $row['impressions'],
                    $row['clicks'],
                    $row['ctr'],
                    $row['cpc'],
                    $row['conversions'],
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    protected function reloadData(): void
    {
        [$from, $to] = $this->resolveRange();
        $model = $this->resolveAttributionModel();
        $filterHash = hash('sha256', json_encode([$from->toDateString(), $to->toDateString(), $model]));
        $cached = ReportCache::query()
            ->where('report_key', 'ads_insights')
            ->where('filter_hash', $filterHash)
            ->first();

        if ($cached && $cached->generated_at && $cached->generated_at->gt(now()->subMinutes(30))) {
            $payload = (array) $cached->payload;
            $this->summary = (array) ($payload['summary'] ?? []);
            $this->sourceRows = (array) ($payload['sources'] ?? []);
            $this->campaignRows = (array) ($payload['campaigns'] ?? []);
            $this->categoryRows = (array) ($payload['categories'] ?? []);
            $this->productRows = (array) ($payload['products'] ?? []);
            $this->utmQuality = (array) ($payload['utm_quality'] ?? []);

            return;
        }

        $summary = $this->buildSummary($from, $to);
        $sources = $this->buildSourceRows($from, $to, $model)->all();
        $campaigns = $this->buildCampaignRows($from, $to)->all();
        $categories = $this->buildCategoryRows($from, $to)->all();
        $products = $this->buildProductRows($from, $to)->all();
        $utmQuality = $this->buildUtmQuality($from, $to);

        $this->summary = $summary;
        $this->sourceRows = $sources;
        $this->campaignRows = $campaigns;
        $this->categoryRows = $categories;
        $this->productRows = $products;
        $this->utmQuality = $utmQuality;

        ReportCache::query()->updateOrCreate(
            ['report_key' => 'ads_insights', 'filter_hash' => $filterHash],
            [
                'payload' => [
                    'summary' => $summary,
                    'sources' => $sources,
                    'campaigns' => $campaigns,
                    'categories' => $categories,
                    'products' => $products,
                    'utm_quality' => $utmQuality,
                ],
                'generated_at' => now(),
            ]
        );
    }

    /**
     * @return array{0:Carbon,1:Carbon}
     */
    protected function resolveRange(): array
    {
        $from = Carbon::parse($this->fromDate ?: now()->subDays(30)->toDateString())->startOfDay();
        $to = Carbon::parse($this->toDate ?: now()->toDateString())->endOfDay();

        if ($to->lt($from)) {
            $to = $from->copy()->endOfDay();
            $this->toDate = $to->toDateString();
        }

        return [$from, $to];
    }

    protected function buildSummary(Carbon $from, Carbon $to): array
    {
        $leads = Lead::query()->whereBetween('created_at', [$from, $to])->count();
        $quoteLeads = Lead::query()->where('type', 'quote')->whereBetween('created_at', [$from, $to])->count();
        $contactLeads = Lead::query()->where('type', 'contact')->whereBetween('created_at', [$from, $to])->count();
        $attributed = AttributionLog::query()
            ->whereBetween('created_at', [$from, $to])
            ->where(function ($query): void {
                $query->whereNotNull('utm_source')
                    ->orWhereNotNull('gclid')
                    ->orWhereNotNull('fbclid');
            })
            ->count();
        $phoneClicks = EventLog::query()->where('event_key', 'click_phone')->whereBetween('created_at', [$from, $to])->count();
        $whatsappClicks = EventLog::query()->where('event_key', 'click_whatsapp')->whereBetween('created_at', [$from, $to])->count();
        $quoteCtaClicks = EventLog::query()->where('event_key', 'click_quote_cta')->whereBetween('created_at', [$from, $to])->count();
        $spend = CampaignSnapshot::query()->whereBetween('snapshot_date', [$from->toDateString(), $to->toDateString()])->sum('spend');

        return [
            'period' => $from->format('Y-m-d') . ' - ' . $to->format('Y-m-d'),
            'leads_total' => $leads,
            'leads_quote' => $quoteLeads,
            'leads_contact' => $contactLeads,
            'attributed' => $attributed,
            'attribution_rate' => $leads > 0 ? round(($attributed / $leads) * 100, 1) : 0.0,
            'spend_total' => round((float) $spend, 2),
            'cpl' => $leads > 0 ? round(((float) $spend / $leads), 2) : 0.0,
            'click_phone' => $phoneClicks,
            'click_whatsapp' => $whatsappClicks,
            'click_quote_cta' => $quoteCtaClicks,
        ];
    }

    protected function buildSourceRows(Carbon $from, Carbon $to, string $model): Collection
    {
        if ($model === 'first_touch') {
            $rows = [];

            AttributionLog::query()
                ->whereBetween('created_at', [$from, $to])
                ->get(['meta'])
                ->each(function (AttributionLog $log) use (&$rows): void {
                    $source = trim((string) data_get($log->meta, 'first_touch.params.utm_source', 'direct'));
                    $source = $source !== '' ? $source : 'direct';
                    $rows[$source] = ($rows[$source] ?? 0) + 1;
                });

            return collect($rows)
                ->map(fn (int $count, string $source): array => ['source' => $source, 'leads' => $count])
                ->sortByDesc('leads')
                ->take(15)
                ->values();
        }

        return AttributionLog::query()
            ->selectRaw("COALESCE(utm_source, 'direct') as source, COUNT(*) as leads")
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('source')
            ->orderByDesc('leads')
            ->limit(15)
            ->get()
            ->map(fn ($row): array => [
                'source' => (string) $row->source,
                'leads' => (int) $row->leads,
            ]);
    }

    protected function buildUtmQuality(Carbon $from, Carbon $to): array
    {
        $logs = AttributionLog::query()
            ->whereBetween('created_at', [$from, $to])
            ->get(['utm_source', 'utm_campaign', 'gclid', 'fbclid']);

        $total = $logs->count();
        $withUtmSource = 0;
        $missingSource = 0;
        $missingCampaign = 0;
        $invalidSource = 0;
        $withGclid = 0;
        $withFbclid = 0;
        $tracked = 0;

        foreach ($logs as $log) {
            $source = trim((string) ($log->utm_source ?? ''));
            $campaign = trim((string) ($log->utm_campaign ?? ''));
            $gclid = trim((string) ($log->gclid ?? ''));
            $fbclid = trim((string) ($log->fbclid ?? ''));

            if ($gclid !== '') {
                $withGclid++;
            }

            if ($fbclid !== '') {
                $withFbclid++;
            }

            if ($source !== '' || $gclid !== '' || $fbclid !== '') {
                $tracked++;
            }

            if ($source === '') {
                $missingSource++;
            } else {
                $withUtmSource++;

                if (! preg_match('/^[a-z0-9._-]+$/i', $source)) {
                    $invalidSource++;
                }
            }

            if ($source !== '' && $campaign === '') {
                $missingCampaign++;
            }
        }

        $coverage = $total > 0 ? round(($withUtmSource / $total) * 100, 1) : 0.0;
        $trackedRate = $total > 0 ? round(($tracked / $total) * 100, 1) : 0.0;
        $qualityScore = $total > 0
            ? round((max(0, $withUtmSource - $invalidSource) / $total) * 100, 1)
            : 0.0;

        return [
            'total_logs' => $total,
            'with_utm_source' => $withUtmSource,
            'missing_source' => $missingSource,
            'missing_campaign' => $missingCampaign,
            'invalid_source' => $invalidSource,
            'with_gclid' => $withGclid,
            'with_fbclid' => $withFbclid,
            'tracked' => $tracked,
            'coverage_rate' => $coverage,
            'tracked_rate' => $trackedRate,
            'quality_score' => $qualityScore,
        ];
    }

    protected function resolveAttributionModel(): string
    {
        return in_array($this->attributionModel, ['last_touch', 'first_touch'], true)
            ? $this->attributionModel
            : 'last_touch';
    }

    protected function buildCampaignRows(Carbon $from, Carbon $to): Collection
    {
        return CampaignSnapshot::query()
            ->selectRaw('platform, campaign_name, SUM(spend) as spend, SUM(clicks) as clicks, SUM(impressions) as impressions, SUM(conversions) as conversions')
            ->whereBetween('snapshot_date', [$from->toDateString(), $to->toDateString()])
            ->groupBy('platform', 'campaign_name')
            ->orderByDesc('spend')
            ->limit(20)
            ->get()
            ->map(function ($row): array {
                $clicks = max(1, (int) $row->clicks);
                $impressions = max(1, (int) $row->impressions);
                $spend = (float) $row->spend;

                return [
                    'platform' => (string) $row->platform,
                    'campaign_name' => (string) ($row->campaign_name ?: '-'),
                    'spend' => round($spend, 2),
                    'clicks' => (int) $row->clicks,
                    'impressions' => (int) $row->impressions,
                    'conversions' => (int) $row->conversions,
                    'ctr' => round(((int) $row->clicks / $impressions) * 100, 2),
                    'cpc' => round($spend / $clicks, 2),
                ];
            });
    }

    protected function buildCategoryRows(Carbon $from, Carbon $to): Collection
    {
        $rows = [];

        Lead::query()
            ->whereBetween('created_at', [$from, $to])
            ->where('type', 'quote')
            ->get(['meta'])
            ->each(function (Lead $lead) use (&$rows): void {
                $category = trim((string) data_get($lead->meta, 'product_category', 'Belirtilmedi'));
                $category = $category !== '' ? $category : 'Belirtilmedi';
                $quantity = (int) data_get($lead->meta, 'quantity', 0);

                if (! isset($rows[$category])) {
                    $rows[$category] = [
                        'category' => $category,
                        'lead_count' => 0,
                        'total_quantity' => 0,
                        'avg_quantity' => 0,
                    ];
                }

                $rows[$category]['lead_count']++;
                $rows[$category]['total_quantity'] += $quantity;
            });

        return collect(array_values($rows))
            ->map(function (array $row): array {
                $row['avg_quantity'] = $row['lead_count'] > 0
                    ? (int) round($row['total_quantity'] / $row['lead_count'])
                    : 0;

                return $row;
            })
            ->sortByDesc('lead_count')
            ->values();
    }

    protected function buildProductRows(Carbon $from, Carbon $to): Collection
    {
        $rows = [];

        Lead::query()
            ->whereBetween('created_at', [$from, $to])
            ->where('type', 'quote')
            ->get(['meta'])
            ->each(function (Lead $lead) use (&$rows): void {
                $product = trim((string) data_get($lead->meta, 'product', 'Belirtilmedi'));
                $product = $product !== '' ? $product : 'Belirtilmedi';
                $quantity = (int) data_get($lead->meta, 'quantity', 0);

                if (! isset($rows[$product])) {
                    $rows[$product] = [
                        'product' => $product,
                        'lead_count' => 0,
                        'total_quantity' => 0,
                        'avg_quantity' => 0,
                    ];
                }

                $rows[$product]['lead_count']++;
                $rows[$product]['total_quantity'] += $quantity;
            });

        return collect(array_values($rows))
            ->map(function (array $row): array {
                $row['avg_quantity'] = $row['lead_count'] > 0
                    ? (int) round($row['total_quantity'] / $row['lead_count'])
                    : 0;

                return $row;
            })
            ->sortByDesc('lead_count')
            ->take(20)
            ->values();
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ($user->isAdmin() || $user->isEditor() || $user->isMarketingManager() || $user->isDeveloper());
    }
}
