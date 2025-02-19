<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class Report extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string $view = 'filament.pages.report';
    protected static ?string $navigationGroup = 'Systemm';
    protected static ?string $title = 'Reports';
    protected static ?int $navigationSort = 1;

    public $startDate, $endDate, $reportType, $productFilter;
    public $showReport = false;

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        $this->reportType = 'sales';
    }
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\DatePicker::make('startDate')
                        ->label('Start Date')
                        ->required()
                        ->native(false),
                    Forms\Components\DatePicker::make('endDate')
                        ->label('End Date')
                        ->required()
                        ->native(false),
                    Forms\Components\Select::make('reportType')
                        ->label('Report Type')
                        ->options([
                            'sales' => 'sales',
                            'purchases' => 'purchases',
                            'products' => 'products',
                            'inventory' => 'inventory',
                        ])
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state) {
                            $this->reportType = $state;
                            $this->showReport = true;
                        })
                        ->native(false),
                    Forms\Components\Select::make('productFilter')
                        ->label('Product Filter')
                        ->options([
                            'all' => 'All Products',
                            'expired' => 'Expired Products',
                            'low_stock' => 'Low Stock Products',
                            'out_of_stock' => 'Out of Stock Products',
                            'near_expiry' => 'Products Near Expiry'
                        ])
                        ->visible(fn($get) => $get('reportType') === 'products')
                        ->default('all')
                        ->required()
                        ->live()
                        ->native(false),
                ])
                ->columns(3)
        ];
    }

    public function getViewData(): array
    {
        $data = [];
        switch ($this->reportType) {
            case 'sales':
                $data = $this->getSalesReport();
                break;
            case 'purchases':
                $data = $this->getPurchasesReport();
                break;
            case 'products':
                $data = $this->getProductsReport();
                break;
            case 'inventory':
                $data = $this->getInventoryReport();
                break;
        }
        return $data instanceof \Illuminate\Database\Eloquent\Collection
            ? $data->all()
            : $data->toArray();
    }
    public function generatePDF()
    {

        $data = $this->getViewData();
        $pdf = PDF::loadView('pdf.report', [
            'data' => $data,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'reportType' => $this->reportType,
            'productFilter' => $this->productFilter ?? 'all',
        ]);
        return response()->streamDownload(
            fn() => print($pdf->output()),
            "report-{$this->reportType}-" . ($this->productFilter ? "{$this->productFilter}-" : "") . now()->format('Y-m-d') . '.pdf'
        );
    }
    private function getSalesReport()
    {
        return Sale::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_sales'),
                DB::raw('SUM(total) as total_amount')
            )
            ->groupBy('date')
            ->get();
    }
    private function getPurchasesReport()
    {
        return Purchase::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_purchases'),
                DB::raw('SUM(total) as total_amount')
            )
            ->groupBy('date')
            ->get();
    }

    private function getProductsReport()
    {
        $query = Product::query();

        switch ($this->productFilter) {
            case 'expired':
                $query->where('expiration', '<', now());
                break;
            case 'low_stock':
                $query->whereRaw('stock <= stock_minimum AND stock > 0');
                break;
            case 'out_of_stock':
                $query->where('stock', '<=', 0);
                break;
            case 'near_expiry':
                $query->whereBetween('expiration', [now(), now()->addDays(30)]);
                break;
        }

        return $query->select('name', 'stock', 'sales_price', 'purchase_price', 'expiration', 'stock_minimum')
            ->orderBy('stock', 'desc')
            ->get();
        // return Product::select('name', 'stock', 'sales_price', 'purchase_price')
        //     ->orderBy('stock', 'desc')
        //     ->get();
    }

    private function getInventoryReport()
    {
        return Product::where('stock', '<=', DB::raw('stock_minimum'))
            ->select('name', 'stock', 'stock_minimum')
            ->orderBy('stock', 'asc')
            ->get();
    }
}
