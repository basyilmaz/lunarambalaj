<?php

namespace App\Filament\Widgets;

use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductCategory;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductsStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $activeProducts = Product::query()->where('is_active', true)->count();
        $categories = ProductCategory::query()->where('is_active', true)->count();
        $activePosts = Post::query()->where('is_active', true)->count();
        $publishedPages = Page::query()->where('is_published', true)->count();

        return [
            Stat::make('Aktif urun', (string) $activeProducts)
                ->description("Kategori: {$categories}")
                ->color('success'),
            Stat::make('Aktif blog', (string) $activePosts)
                ->description('Yayinda olan yazilar')
                ->color('warning'),
            Stat::make('Yayindaki sayfa', (string) $publishedPages)
                ->description('Kurumsal icerik durumu')
                ->color('primary'),
        ];
    }
}
