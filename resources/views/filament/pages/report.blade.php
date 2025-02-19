<x-filament-panels::page>
    <div class="space-y-6">
        {{ $this->form }}

        @if ($showReport)
            <x-filament::section>
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
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">Producto</th>
                                    <th class="px-4 py-2">Stock</th>
                                    <th class="px-4 py-2">Precio Venta</th>
                                    <th class="px-4 py-2">Precio Compra</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($this->getViewData() as $product)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $product->name }}</td>
                                        <td class="border px-4 py-2">{{ $product->stock }}</td>
                                        <td class="border px-4 py-2">${{ number_format($product->sales_price, 2) }}</td>
                                        <td class="border px-4 py-2">${{ number_format($product->purchase_price, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-gray-500">
                                            No hay productos para mostrar
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @elseif($this->reportType === 'inventory')
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2">Producto</th>
                                    <th class="px-4 py-2">Stock Actual</th>
                                    <th class="px-4 py-2">Stock Mínimo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($this->getViewData() as $product)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $product->name }}</td>
                                        <td class="border px-4 py-2">{{ $product->stock }}</td>
                                        <td class="border px-4 py-2">{{ $product->stock_minimum }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-gray-500">
                                            No hay productos con stock bajo
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
