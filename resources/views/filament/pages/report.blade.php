<x-filament-panels::page>
    <div class="space-y-6">
        {{ $this->form }}

        @if ($showReport)

            <div class="flex justify-end mb-4">
                <x-filament::button wire:click="generatePDF" icon="heroicon-o-document-arrow-down" class="ml-auto">
                    Descargar PDF
                </x-filament::button>
            </div>

            @if ($this->reportType === 'sales')
                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-4">
                        @forelse ($this->getViewData() as $sale)
                            <x-filament::card>
                                <div class="text-lg font-medium">{{ $sale->date }}</div>
                                <div class="mt-2">
                                    <div>Ventas: {{ $sale->total_sales }}</div>
                                    <div>Total: ${{ number_format($sale->total_amount, 2) }}</div>
                                </div>
                            </x-filament::card>
                        @empty
                            <div class="col-span-3 text-center text-gray-500">
                                No hay datos para mostrar en el rango de fechas seleccionado
                            </div>
                        @endforelse
                    </div>
                </div>
            @elseif($this->reportType === 'purchases')
                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-4">
                        @forelse ($this->getViewData() as $purchase)
                            <x-filament::card>
                                <div class="text-lg font-medium">{{ $purchase->date }}</div>
                                <div class="mt-2">
                                    <div>Compras: {{ $purchase->total_purchases }}</div>
                                    <div>Total: ${{ number_format($purchase->total_amount, 2) }}</div>
                                </div>
                            </x-filament::card>
                        @empty
                            <div class="col-span-3 text-center text-gray-500">
                                No hay datos para mostrar en el rango de fechas seleccionado
                            </div>
                        @endforelse
                    </div>
                </div>
            @elseif($this->reportType === 'products')
                <div
                    class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
                    <x-table>
                        <x-slot name="head">
                            <x-th>Producto</x-th>
                            <x-th>Stock</x-th>
                            <x-th>Precio Venta</x-th>
                            <x-th>Precio Compra</x-th>
                        </x-slot>

                        @forelse ($this->getViewData() as $product)
                            <tr>
                                <x-td>{{ $product->name }}</x-td>
                                <x-td>{{ $product->stock }}</x-td>
                                <x-td>${{ number_format($product->sales_price, 2) }}</x-td>
                                <x-td>${{ number_format($product->purchase_price, 2) }}
                                </x-td>
                            </tr>
                        @empty
                            <tr>
                                <x-td colspan="4" class="text-center">
                                    No hay productos para mostrar
                                </x-td>
                            </tr>
                        @endforelse

                    </x-table>
                </div>
            @elseif($this->reportType === 'inventory')
                <div
                    class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
                    <x-table class="w-full">
                        <x-slot name="head">

                            <x-th>Producto</x-th>
                            <x-th>Stock Actual</x-th>
                            <x-th>Stock Mínimo</x-th>

                        </x-slot>

                        @forelse ($this->getViewData() as $product)
                            <tr>
                                <x-td>{{ $product->name }}</x-td>
                                <x-td>{{ $product->stock }}</x-td>
                                <x-td>{{ $product->stock_minimum }}</x-td>
                            </tr>
                        @empty
                            <tr>
                                <x-td colspan="3" class="text-center">
                                    No hay productos con stock bajo
                                </x-td>
                            </tr>
                        @endforelse

                    </x-table>
                </div>
            @endif

        @endif
    </div>
</x-filament-panels::page>
