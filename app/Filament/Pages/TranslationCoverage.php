<?php

namespace App\Filament\Pages;

use App\Models\CaseStudy;
use App\Models\Faq;
use App\Models\Language;
use App\Models\Page as CmsPage;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ServiceItem;
use App\Models\Testimonial;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;

class TranslationCoverage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-language';

    protected static ?string $navigationGroup = 'Analiz ve Reklam';

    protected static ?string $navigationLabel = 'Translation Coverage';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.translation-coverage';

    /**
     * @var array<int, string>
     */
    public array $languages = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $summaryRows = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $missingRows = [];

    public function mount(): void
    {
        $this->languages = Language::query()->pluck('code')->all();
        if (empty($this->languages)) {
            $this->languages = config('site.locales', ['tr', 'en']);
        }

        $this->buildCoverage();
    }

    protected function buildCoverage(): void
    {
        $this->summaryRows = [];
        $this->missingRows = [];

        $this->appendCoverage('Urunler', Product::query()->with('translations')->get(), 'filament.admin.resources.products.edit', fn (Product $record): string => $record->translation('tr')?->name ?? ('#' . $record->id));
        $this->appendCoverage('Blog', Post::query()->with('translations')->get(), 'filament.admin.resources.posts.edit', fn (Post $record): string => $record->translation('tr')?->title ?? ('#' . $record->id));
        $this->appendCoverage('Sayfalar', CmsPage::query()->with('translations')->get(), 'filament.admin.resources.pages.edit', fn (CmsPage $record): string => $record->type);
        $this->appendCoverage('SSS', Faq::query()->with('translations')->get(), 'filament.admin.resources.faqs.edit', fn (Faq $record): string => $record->translation('tr')?->question ?? ('#' . $record->id));
        $this->appendCoverage('Hizmetler', ServiceItem::query()->with('translations')->get(), 'filament.admin.resources.service-items.edit', fn (ServiceItem $record): string => $record->translation('tr')?->title ?? ('#' . $record->id));
        $this->appendCoverage('Kategoriler', ProductCategory::query()->with('translations')->get(), 'filament.admin.resources.product-categories.edit', fn (ProductCategory $record): string => $record->translation('tr')?->name ?? ('#' . $record->id));
        $this->appendCoverage('Vaka Analizleri', CaseStudy::query()->with('translations')->get(), 'filament.admin.resources.case-studies.edit', fn (CaseStudy $record): string => $record->translation('tr')?->title ?? ('#' . $record->id));
        $this->appendCoverage('Yorumlar', Testimonial::query()->with('translations')->get(), 'filament.admin.resources.testimonials.edit', fn (Testimonial $record): string => $record->author_name ?? ('#' . $record->id));
    }

    /**
     * @param Collection<int, mixed> $records
     */
    protected function appendCoverage(string $module, Collection $records, string $routeName, callable $titleResolver): void
    {
        $total = $records->count();
        $full = 0;

        foreach ($records as $record) {
            $translated = $record->translations->pluck('lang')->unique()->values()->all();
            $missing = array_values(array_diff($this->languages, $translated));
            if (count($missing) === 0) {
                $full++;
                continue;
            }

            $this->missingRows[] = [
                'module' => $module,
                'record_id' => $record->id,
                'title' => $titleResolver($record),
                'missing' => $missing,
                'edit_url' => route($routeName, ['record' => $record]),
            ];
        }

        $this->summaryRows[] = [
            'module' => $module,
            'total' => $total,
            'full' => $full,
            'missing' => max(0, $total - $full),
            'percent' => $total > 0 ? round(($full / $total) * 100, 1) : 0.0,
        ];
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user !== null
            && ($user->isAdmin() || $user->isEditor() || $user->isMarketingManager() || $user->isDeveloper());
    }
}
