<table width="100%">
    <tr>
        <td valign="top">
            <img class='logo' alt='Logo' width="190"
                src='data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/' . $setting->logo))) }}'>
        </td>
        <td align="right">
            <h3 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                {{ $setting->company_name }}</h3>
            <div class="fi-ta-text text-sm text-gray-500 dark:text-gray-400">
                <p class="mb-1">{{ $setting->commercial_name }}</p>
                <p class="mb-1">{{ $setting->ruc }}</p>
                <p class="mb-1">{{ $setting->address }}</p>
                <p class="mb-1">{{ $setting->phone }}</p>
                <p class="mb-1">{{ $setting->email }}</p>
            </div>
        </td>
    </tr>
</table>
<div class="mt-6">
    @if ($this->reportType === 'sales')
        <div
            class="w-full fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
            <x-table>
                <x-slot name="head">
                    <x-th>Factura</x-th>
                    <x-th>Cliente</x-th>
                    <x-th>Usuario</x-th>
                    <x-th>Sub. Total</x-th>
                    <x-th>IGV</x-th>
                    <x-th>Total</x-th>
                    <x-th>Fecha</x-th>
                </x-slot>

                @forelse ($this->getViewData() as $sale)
                    <tr>
                        <x-td>{{ $sale->invoice_number }}</x-td>
                        <x-td>{{ $sale->customer->name }}</x-td>
                        <x-td>{{ $sale->user->name }}</x-td>
                        <x-td>${{ number_format($sale->subtotal, 2) }}</x-td>
                        <x-td>${{ number_format($sale->tax, 2) }}</x-td>
                        <x-td>${{ number_format($sale->total, 2) }}</x-td>
                        <x-td>{{ $sale->created_at->format('Y-m-d') }}</x-td>
                    </tr>
                @empty
                    <tr>
                        <x-td colspan="7" class="text-center">
                            No hay ventas para mostrar en el rango de fechas seleccionado
                        </x-td>
                    </tr>
                @endforelse
            </x-table>
        </div>
    @elseif($this->reportType === 'purchases')
      

        <div
            class="w-full fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
            <x-table>
                <x-slot name="head">
                    <x-th>Compra</x-th>
                    <x-th>Usuario</x-th>
                    <x-th>Proveedor</x-th>
                    <x-th>Total</x-th>
                    <x-th>Fecha</x-th>
                </x-slot>

                @forelse ($this->getViewData() as $purchase)
                    <tr>
                        <x-td>{{ $purchase->purchase_number }}</x-td>
                        <x-td>{{ $purchase->user->name }}</x-td>
                        <x-td>{{ $purchase->supplier->name }}</x-td>
                        <x-td>S/. {{ number_format($purchase->total, 2) }}</x-td>
                        <x-td>{{ $purchase->created_at->format('Y-m-d') }}</x-td>
                    </tr>
                @empty
                    <tr>
                        <x-td colspan="5" class="text-center">
                            No hay compras para mostrar en el rango de fechas seleccionado
                        </x-td>
                    </tr>
                @endforelse
            </x-table>
        </div>
    @elseif($this->reportType === 'products')
      <div class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
        <x-table>
            <x-slot name="head">
                <x-th>Producto</x-th>
                <x-th>Categoría</x-th>
                <x-th>Stock</x-th>
                <x-th>Stock Min.</x-th>
                {{-- <x-th>Pre. Compra</x-th>
                <x-th>Pre. Venta</x-th> --}}
                <x-th>Caducidad</x-th>
                <x-th>Estado</x-th>
            </x-slot>

            @forelse ($this->getViewData() as $product)
                <tr>
                    <x-td>{{ $product->name }}</x-td>
                    <x-td>{{ $product->category->name }}</x-td>
                    <x-td>
                        <div @class([
                            'px-2 py-1 rounded-full text-center',
                            'bg-red-100 text-red-800' => $product->stock <= 0,
                            'bg-yellow-100 text-yellow-800' => $product->stock <= $product->stock_minimum,
                            'bg-green-100 text-green-800' => $product->stock > $product->stock_minimum,
                        ])>
                            {{ $product->stock }}
                        </div>
                    </x-td>
                    <x-td>{{ $product->stock_minimum }}</x-td>
                    {{-- <x-td>S/. {{ number_format($product->purchase_price, 2) }}</x-td>
                    <x-td>S/. {{ number_format($product->sales_price, 2) }}</x-td> --}}
                    <x-td>
                        <div @class([
                            'px-2 py-1 rounded-full text-center',
                            'bg-red-100 text-red-800' => $product->status === 'expired',
                            'bg-yellow-100 text-yellow-800' => $product->status === 'near_expiry',
                            'bg-green-100 text-green-800' => $product->status === 'normal',
                        ])>
                            @if($product->expiration)
                             {{ \Carbon\Carbon::parse($product->expiration)->format('Y-m-d') }}
                                <br>
                                <span class="text-xs">
                                    @if($product->days_until_expiry < 0)
                                        Caducado hace {{ abs($product->days_until_expiry) }} días
                                    @elseif($product->days_until_expiry == 0)
                                        Caduca hoy
                                    @else
                                        Caduca en {{ $product->days_until_expiry }} días
                                    @endif
                                </span>
                            @else
                                N/A
                            @endif
                        </div>
                    </x-td>
                    <x-td>
                        <div @class([
                            'flex items-center gap-2',
                            'text-danger-600' => in_array($product->status, ['expired', 'out_of_stock']),
                            'text-rose-600' => in_array($product->status, ['near_expiry', 'low_stock']),
                            'text-primary-600' => $product->status === 'normal',
                        ])>
                            @php
                                $icon = match($product->status) {
                                    'expired' => 'heroicon-o-x-circle',
                                    'near_expiry' => 'heroicon-o-clock',
                                    'out_of_stock' => 'heroicon-o-minus-circle',
                                    'low_stock' => 'heroicon-o-exclamation-circle',
                                    default => 'heroicon-o-check-circle'
                                };
                                
                                $status = match($product->status) {
                                    'expired' => 'Caducado',
                                    'near_expiry' => 'Próximo a caducar',
                                    'out_of_stock' => 'Sin stock',
                                    'low_stock' => 'Stock bajo',
                                    default => 'Normal'
                                };
                            @endphp
                            <x-icon name="{{ $icon }}" class="w-5 h-5" />
                            {{ $status }}
                        </div>
                    </x-td>
                </tr>
            @empty
                <tr>
                    <x-td colspan="8" class="text-center">
                        No hay productos que coincidan con el filtro seleccionado
                    </x-td>
                </tr>
            @endforelse
        </x-table>
    </div>
    @elseif($this->reportType === 'inventory')
   <div class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
        <x-table>
            <x-slot name="head">
                <x-th>Producto</x-th>
                {{-- <x-th>Categoría</x-th> --}}
                <x-th>Stock Actual</x-th>
                <x-th>Stock Mínimo</x-th>
                <x-th>Estado</x-th>
                {{-- <x-th>Precio Compra</x-th>
                <x-th>Precio Venta</x-th> --}}
            </x-slot>

            @forelse ($this->getViewData() as $product)
                <tr>
                    <x-td>{{ $product->name }}</x-td>
                    {{-- <x-td>{{ $product->category->name }}</x-td> --}}
                    <x-td>
                        <div @class([
                            'px-2 py-1 rounded-full text-center',
                            'bg-red-100 text-red-800' => $product->stock_status === 'critical',
                            'bg-yellow-100 text-yellow-800' => $product->stock_status === 'warning',
                            'bg-green-100 text-green-800' => $product->stock_status === 'normal',
                        ])>
                            {{ $product->stock }}
                        </div>
                    </x-td>
                    <x-td>{{ $product->stock_minimum }}</x-td>
                    <x-td>
                        <div @class([
                            'flex items-center gap-2',
                            'text-red-600' => $product->stock_status === 'critical',
                            'text-yellow-600' => $product->stock_status === 'warning',
                            'text-green-600' => $product->stock_status === 'normal',
                        ])>
                            <x-icon name="{{ $product->stock_status === 'critical' ? 'heroicon-o-exclamation-triangle' : ($product->stock_status === 'warning' ? 'heroicon-o-exclamation-circle' : 'heroicon-o-check-circle') }}" class="w-5 h-5" />
                            {{ ucfirst($product->stock_status) }}
                        </div>
                    </x-td>
                    {{-- <x-td>S/. {{ number_format($product->purchase_price, 2) }}</x-td>
                    <x-td>S/. {{ number_format($product->sales_price, 2) }}</x-td> --}}
                </tr>
            @empty
                <tr>
                    <x-td colspan="7" class="text-center">
                        No hay productos con stock bajo
                    </x-td>
                </tr>
            @endforelse
        </x-table>
    </div>
    @endif

</div>
