<?php

namespace App\Filament\Widgets;

use App\Models\Purchase;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PurchaseProductChart extends ChartWidget
{
    protected static ?string $heading = 'Compras Chart';
    protected static ?int $sort = 2;


    protected function getData(): array
    {
        $data = $this->getPurchaseData();

        return [
            'datasets' => [
                [
                    'label' => 'Total Purchases',
                    'data' => $data['totals'],
                    'borderColor' => '#DC2626',
                    'fill' => false,
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getPurchaseData(): array
    {
        $now = Carbon::now();
        $months = collect(range(1, 12))->map(function ($month) use ($now) {
            return Carbon::create($now->year, $month, 1);
        });

        $purchasesByMonth = Purchase::whereYear('created_at', $now->year)
            ->where('status', '1') // Only accepted purchases
            ->selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $totals = $months->map(function ($month) use ($purchasesByMonth) {
            return $purchasesByMonth[$month->month] ?? 0;
        })->toArray();

        $labels = $months->map(fn ($month) => $month->format('M'))->toArray();

        return [
            'totals' => $totals,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
