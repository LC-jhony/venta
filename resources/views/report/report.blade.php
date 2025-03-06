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

    <div
        style="width: 100%; margin-top: 1.5rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: flex-start;">
        <div style="flex: 1; text-align: left;">
            <h4
                style="font-size: 2.5rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; margin: 0;">
                Reporte de
                {{ ucfirst(
                    match ($reportType) {
                        'sales' => 'Ventas',
                        'purchases' => 'Compras',
                        'products' => 'Productos',
                        'inventory' => 'Inventario',
                        default => $reportType,
                    },
                ) }}
            </h4>
        </div>

        <div style="text-align: right;">
            <div style="border: 1px solid #ccc; border-radius: 8px; padding: 10px; background-color: #f9f9f9;">
                <p style="font-size: 16px; margin: 0;">
                    Desde: <span
                        style="padding-left: 0.5rem;">{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</span>
                </p>
                <p style="font-size: 16px; margin: 0;">
                    Hasta: <span
                        style="padding-left: 0.5rem;">{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</span>
                </p>
            </div>
        </div>
    </div>

    <x-table>
        <x-slot name="head">
            @if ($reportType === 'sales')
                <tr>
                    <x-th style="text-align: left;">Factura</x-th>
                    <x-th style="text-align: left;">Cliente</x-th>
                    <x-th style="text-align: left;">Usuario</x-th>
                    <x-th>Sub. Total</x-th>
                    <x-th>IGV</x-th>
                    <x-th>Total</x-th>
                    <x-th>Fecha</x-th>
                </tr>
            @elseif($reportType === 'purchases')
                <tr>
                    <x-th style="text-align: left;">Compra</x-th>
                    <x-th style="text-align: left;">Usuario</x-th>
                    <x-th style="text-align: left;">Proveedor</x-th>
                    <x-th style="text-align: left;">Total</x-th>
                    <x-th>Fecha</x-th>
                </tr>
            @elseif($reportType === 'products')
                <tr>
                    <x-th style="text-align: left;">Producto</x-th>
                    <x-th style="text-align: left;">Categoría</x-th>
                    <x-th>Stock</x-th>
                    <x-th>Stock Min.</x-th>
                    {{-- <x-th>Pre. Compra</x-th>
                    <x-th>Pre. Venta</x-th> --}}
                    <x-th>Estado</x-th>
                </tr>
            @elseif($reportType === 'inventory')
                <tr>
                    <x-th style="text-align: left;">Producto</x-th>
                    {{-- <th style="text-align: left;">Categoría</th> --}}
                    <x-th>Stock Actual</x-th>
                    <x-th>Stock Mínimo</x-th>
                    <x-th>Estado</x-th>
                </tr>
            @endif
        </x-slot>
        @foreach ($data as $item)
            @if ($reportType === 'sales')
                <tr>
                    <x-td>{{ $item->invoice_number }}</x-td>
                    <x-td>{{ $item->customer->name }}</x-td>
                    <x-td>{{ $item->user->name }}</x-td>
                    <x-td align="center">S/. {{ number_format($item->subtotal, 2) }}</x-td>
                    <x-td align="center">S/. {{ number_format($item->tax, 2) }}</x-td>
                    <x-td align="center">S/. {{ number_format($item->total, 2) }}</x-td>
                    <x-td align="center">{{ $item->created_at->format('d/m/Y') }}</x-td>
                </tr>
            @elseif($reportType === 'purchases')
                <tr>
                    <x-td>{{ $item->purchase_number }}</x-td>
                    <x-td>{{ $item->user->name }}</x-td>
                    <x-td>{{ $item->supplier->name }}</x-td>
                    <x-td align="center">S/. {{ number_format($item->total, 2) }}</x-td>
                    <x-td align="center">{{ $item->created_at->format('d/m/Y') }}</x-td>
                </tr>
            @elseif($reportType === 'products')
                <tr>
                    <x-td style="text-align: left;">{{ $item->name }}</x-td>
                    <x-td>{{ $item->category->name }}</x-td>
                    <x-td align="center">{{ $item->stock }}</x-td>
                    <x-td align="center">{{ $item->stock_minimum }}</x-td>
                    {{-- <x-td align="center">S/. {{ number_format($item->purchase_price, 2) }}</x-td>
                    <x-td align="center">S/. {{ number_format($item->sales_price, 2) }}</x-td> --}}
                    <x-td align="center">{{ ucfirst($item->status) }}</x-td>
                </tr>
            @elseif($reportType === 'inventory')
                <tr>
                    <x-td>{{ $item->name }}</x-td>
                    {{-- // <td>{{ $item->category->name }}</td> --}}
                    <x-td align="center">{{ $item->stock }}</x-td>
                    <x-td align="center">{{ $item->stock_minimum }}</x-td>
                    <x-td align="center">{{ ucfirst($item->stock_status) }}</x-td>
                </tr>
            @endif
        @endforeach
    </x-table>
    <div>

    </div>
</div>
