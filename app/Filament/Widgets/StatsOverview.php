<?php

namespace App\Filament\Widgets;

// use Filament\Widgets\StatsOverviewWidget as BaseWidget;
// use Filament\Widgets\StatsOverviewWidget\Stat;

use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget as BaseWidget;
use EightyNine\FilamentAdvancedWidget\AdvancedStatsOverviewWidget\Stat;
use Filament\Support\Enums\IconPosition;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Purchase;


class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Productos', Product::count())
                ->icon('heroicon-o-cube')
                ->description(Product::where('created_at', '>=', now()->subMonth())->count() . ' nuevos este mes')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->descriptionColor('success')
                ->iconColor('success')
                ->chart(
                    Product::query()
                        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                        ->where('created_at', '>=', now()->subDays(30))
                        ->groupBy('date')
                        ->pluck('count')->toArray()
                )
                ->chartColor('success'),
            Stat::make('Compras', Purchase::count())
                ->icon('heroicon-o-shopping-cart')
                ->description('S/. ' . number_format(Purchase::where('created_at', '>=', now()->subMonth())->sum('total'), 2) . ' este mes')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->descriptionColor('danger')
                ->iconColor('danger')
                ->chart(
                    Product::query()
                        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                        ->where('created_at', '>=', now()->subDays(30))
                        ->groupBy('date')
                        ->pluck('count')->toArray()
                )
                ->chartColor('danger'),
            Stat::make('Ventas', Sale::count())
                ->icon('gmdi-point-of-sale-tt')
                ->description('S/. ' . number_format(Sale::where('created_at', '>=', now()->subMonth())->sum('total'), 2) . ' este mes')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->descriptionColor('warning')
                ->iconColor('warning')
                ->chart(
                    Product::query()
                        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                        ->where('created_at', '>=', now()->subDays(30))
                        ->groupBy('date')
                        ->pluck('count')->toArray()
                )
                ->chartColor('warning'),
        ];
    }
}
