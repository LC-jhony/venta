<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\Sale;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;


class Report extends Page
{
    protected static ?string $navigationIcon = 'lineawesome-file-pdf';

    protected static string $view = 'filament.pages.report';

    protected static ?string $navigationGroup = 'Sistema';

    protected static ?string $title = 'Reporte';

    protected static ?int $navigationSort = 1;

    public $startDate, $endDate, $reportType, $productFilter;
    public $showReport = false;

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        $this->reportType = 'sales';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Reporte del Sistema')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Card::make('Selecione Datos')
                                    ->icon('heroicon-o-funnel')
                                    ->schema([
                                        Forms\Components\DatePicker::make('startDate')
                                            ->label('Fecha Desde')
                                            ->prefixIcon('heroicon-o-calendar')
                                            ->required()
                                            ->native(false),
                                        Forms\Components\DatePicker::make('endDate')
                                            ->label('Fecha Hasta')
                                            ->prefixIcon('heroicon-o-calendar')
                                            ->required()
                                            ->native(false),
                                        Forms\Components\Select::make('reportType')
                                            ->label('Tipo de Reporte')
                                            ->options([
                                                'sales' => 'Ventas',
                                                'purchases' => 'Compras',
                                                'products' => 'Productos',
                                                'inventory' => 'Inventario',
                                            ])
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function ($state) {
                                                $this->reportType = $state;
                                                $this->showReport = true;
                                            })
                                            ->native(false),
                                        Forms\Components\Select::make('productFilter')
                                            ->label('Filtrar Productos')
                                            ->options([
                                                'all' => 'todo',
                                                'expired' => 'Productos caducados',
                                                'low_stock' => 'Productos con poco stock',
                                                'out_of_stock' => 'Productos Fuera de Stock',
                                                'near_expiry' => 'Productos próximos a caducar',
                                            ])
                                            ->visible(fn($get) => $get('reportType') === 'products')
                                            ->default('all')
                                            ->required()
                                            ->live()
                                            ->native(false),
                                    ])
                                    ->columnSpan([
                                        'default' => 'full',
                                        'md' => 4,
                                    ]),
                                Forms\Components\Card::make('Previsualización')
                                    ->icon('heroicon-o-eye')
                                    ->headerActions([
                                        Forms\Components\Actions\Action::make('generate')
                                            ->label('Imprimir')
                                            ->icon('fluentui-print-48-o')
                                            ->outlined()
                                            ->action(function () {
                                                $data = $this->getViewData();
                                                $pdf = PDF::loadView('pdf.report', [
                                                    'data' => $data,
                                                    'startDate' => $this->startDate,
                                                    'endDate' => $this->endDate,
                                                    'reportType' => $this->reportType,
                                                    'productFilter' => $this->productFilter ?? 'all',
                                                    'setting' => \App\Models\Setting::first(),
                                                ]);
                                                return response()->streamDownload(
                                                    fn() => print($pdf->output()),
                                                    "reporte-{$this->reportType}-" .
                                                        ($this->productFilter ? "{$this->productFilter}-" : '') .
                                                        now()->format('Y-m-d') . '.pdf'
                                                );
                                            })

                                    ])
                                    ->schema([
                                        Forms\Components\ViewField::make('report')
                                            ->view('report.report', [
                                                'setting' => \App\Models\Setting::first(),
                                                'startDate' => $this->startDate,
                                                'endDate' => $this->endDate,
                                                'reportType' => $this->reportType,
                                                'productFilter' => $this->productFilter ?? 'all',
                                                'data' => $this->getViewData(),

                                            ])
                                    ])
                                    ->columnSpan([
                                        'default' => 'full',
                                        'md' => 8,
                                    ]),
                            ])
                            ->columns(12)
                    ])
            ]);
    }

    public function getViewData(): array
    {
        if (!$this->reportType) {
            return [];
        }

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

        if ($data instanceof \Illuminate\Database\Eloquent\Collection) {
            return $data->all();
        }

        if (is_array($data)) {
            return $data;
        }

        return [];
    }

    private function getSalesReport()
    {
        return Sale::with(['customer', 'user'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->select([
                'id',
                'customer_id',
                'user_id',
                'invoice_number',
                'subtotal',
                'tax',
                'total',
                'created_at'
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }
    private function getPurchasesReport()
    {
        return Purchase::with(['user', 'supplier'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->select([
                'id',
                'user_id',
                'supplier_id',
                'purchase_number',
                'total',
                'created_at'

            ])
            ->orderBy('created_at', 'desc')
            ->get();
        // return Purchase::whereBetween('created_at', [$this->startDate, $this->endDate])
        //     ->select(
        //         DB::raw('DATE(created_at) as date'),
        //         DB::raw('COUNT(*) as total_purchases'),
        //         DB::raw('SUM(total) as total_amount')
        //     )
        //     ->groupBy('date')
        //     ->get();
    }
    private function getProductsReport()
    {
        $query = Product::query()
            ->with('category')
            ->select([
                'id',
                'name',
                'stock',
                'sales_price',
                'purchase_price',
                'expiration',
                'stock_minimum',
                'category_id',
                DB::raw('DATEDIFF(expiration, CURDATE()) as days_until_expiry'),
                DB::raw('CASE 
                WHEN expiration < CURDATE() THEN "caducado"
                WHEN DATEDIFF(expiration, CURDATE()) <= 30 THEN "por caducar"
                WHEN stock <= 0 THEN "sin stock"
                WHEN stock <= stock_minimum THEN "stock bajo"
                ELSE "normal"
            END as status')
            ]);

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

        return $query->orderBy('status', 'desc')
            ->orderBy('stock', 'asc')
            ->get();
    }
    private function getInventoryReport()
    {
        return Product::query()
            ->with('category')  // Incluimos la relación con categoría
            ->where('stock', '<=', DB::raw('stock_minimum * 1.5')) // Alertar cuando stock está al 150% del mínimo
            ->select([
                'id',
                'name',
                'stock',
                'stock_minimum',
                'category_id',

                DB::raw('CASE 
                WHEN stock <= stock_minimum THEN "critico"  
                WHEN stock <= (stock_minimum * 1.5) THEN "advertencia"
                ELSE "normal"
            END as stock_status')
            ])
            ->orderBy('stock_status', 'desc')
            ->orderBy('stock', 'asc')
            ->get();
    }
}
