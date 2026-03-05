<?php

namespace App\Jobs;

use App\Services\Ads\OfflineConversionExportService;
use Carbon\CarbonImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportOfflineConversionsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $platform,
        public string $fromDate,
        public string $toDate
    ) {
    }

    public function handle(OfflineConversionExportService $service): void
    {
        $from = CarbonImmutable::parse($this->fromDate)->startOfDay();
        $to = CarbonImmutable::parse($this->toDate)->endOfDay();

        $service->exportPlatform($this->platform, $from, $to);
    }
}

