<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SaleProductChart extends ChartWidget
{
    protected static ?string $heading = 'Ventas Chart';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = $this->getSalesData();

        return [
            'datasets' => [
                [
                    'label' => 'Total Sales',
                    'data' => $data['totals'],
                    'borderColor' => '#36A2EB',
                    'fill' => false,
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getSalesData(): array
    {
        $now = Carbon::now();
        $months = collect(range(1, 12))->map(function ($month) use ($now) {
            return Carbon::create($now->year, $month, 1);
        });

        $salesByMonth = Sale::whereYear('created_at', $now->year)
            ->selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $totals = $months->map(function ($month) use ($salesByMonth) {
            return $salesByMonth[$month->month] ?? 0;
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
