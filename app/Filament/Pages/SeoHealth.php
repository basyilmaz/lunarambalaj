<?php

namespace App\Filament\Pages;

use App\Models\PageTranslation;
use App\Models\PostTranslation;
use App\Models\ProductTranslation;
use Filament\Pages\Page;

class SeoHealth extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass-circle';

    protected static ?string $navigationGroup = 'Analiz ve Reklam';

    protected static ?string $navigationLabel = 'SEO Health';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.seo-health';

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $rows = [];

    /**
     * @var array<string, int|float>
     */
    public array $summary = [];

    public function mount(): void
    {
        $this->reloadRows();
    }

    protected function reloadRows(): void
    {
        $rows = [];
        $total = 0;
        $complete = 0;

        $append = function (string $type, $items, string $routeName) use (&$rows, &$total, &$complete): void {
            foreach ($items as $item) {
                $titleLen = mb_strlen((string) $item->seo_title);
                $descLen = mb_strlen((string) $item->seo_desc);
                $issues = [];

                if (blank($item->seo_title)) {
                    $issues[] = 'seo_title eksik';
                } elseif ($titleLen > 60) {
                    $issues[] = 'seo_title > 60';
                }

                if (blank($item->seo_desc)) {
                    $issues[] = 'seo_desc eksik';
                } elseif ($descLen > 160) {
                    $issues[] = 'seo_desc > 160';
                }

                $total++;
                if (count($issues) === 0) {
                    $complete++;
                }

                $rows[] = [
                    'type' => $type,
                    'lang' => $item->lang,
                    'title' => $item->title ?? $item->name ?? '-',
                    'slug' => $item->slug,
                    'seo_title_len' => $titleLen,
                    'seo_desc_len' => $descLen,
                    'issues' => $issues,
                    'edit_url' => route($routeName, ['record' => $item]),
                ];
            }
        };

        $append('Urun', ProductTranslation::query()->get(), 'filament.admin.resources.product-translations.edit');
        $append('Blog', PostTranslation::query()->get(), 'filament.admin.resources.post-translations.edit');
        $append('Sayfa', PageTranslation::query()->get(), 'filament.admin.resources.page-translations.edit');

        $this->rows = collect($rows)
            ->sortByDesc(fn (array $row): int => count($row['issues']))
            ->values()
            ->all();

        $this->summary = [
            'total' => $total,
            'complete' => $complete,
            'missing' => max(0, $total - $complete),
            'percent' => $total > 0 ? round(($complete / $total) * 100, 1) : 0.0,
        ];
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ($user->isAdmin() || $user->isEditor() || $user->isMarketingManager() || $user->isDeveloper());
    }
}
