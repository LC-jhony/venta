<x-app>
    <header>
        <table width="100%">
            <tr>
                <td valign="top">
                    <img class='logo' alt='Logo' width="150"
                        src='data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/' . $setting->logo))) }}'>
                </td>
                <td align="right">
                    <h3>{{ $setting->company_name }}</h3>
                    <pre>
                        {{ $setting->commercial_name }}
                        {{ $setting->ruc }}
                        {{ $setting->address }}
                        {{ $setting->phone }}
                        {{ $setting->email }}
                    </pre>
                </td>
            </tr>

        </table>
    </header>

    <div>
        <table style="width: 100%; margin-top: -2.5rem; margin-bottom: 1.5rem;">
                <tr>
                    <td style="width: 60%; text-align: left;">
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
                </td>
                <td style="width: 40%; text-align: right; vertical-align: top;">
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
                </td>
            </tr>                               
        </table>
    </div>

    <div>
        <table width="100%" style="border-collapse: collapse;">
            <thead class="head" style="background-color: lightgray;">
                @if ($reportType === 'sales')
                    <tr>
                        <th style="text-align: left;">Factura</th>
                        <th style="text-align: left;">Cliente</th>
                        <th style="text-align: left;">Usuario</th>
                        <th>Sub. Total</th>
                        <th>IGV</th>
                        <th>Total</th>
                        <th>Fecha</th>
                    </tr>
                @elseif($reportType === 'purchases')
                    <tr>
                        <th style="text-align: left;">Compra</th>
                        <th style="text-align: left;">Usuario</th>
                        <th style="text-align: left;">Proveedor</th>
                        <th style="text-align: left;">Total</th>
                        <th>Fecha</th>
                    </tr>
                @elseif($reportType === 'products')
                    <tr>
                        <th style="text-align: left;">Producto</th>
                        <th style="text-align: left;">Categoría</th>
                        <th>Stock</th>
                        <th>Stock Min.</th>
                        <th>Pre. Compra</th>
                        <th>Pre. Venta</th>
                        <th>Estado</th>
                    </tr>
                @elseif($reportType === 'inventory')
                    <tr>
                        <th style="text-align: left;">Producto</th>
                        {{-- <th style="text-align: left;">Categoría</th> --}}
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                        <th>Estado</th>
                    </tr>
                @endif
            </thead>
            <tbody class="body">
                @foreach ($data as $item)
                    @if ($reportType === 'sales')
                        <tr>
                            <td>{{ $item->invoice_number }}</td>
                            <td>{{ $item->customer->name }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td align="center">S/. {{ number_format($item->subtotal, 2) }}</td>
                            <td align="center">S/. {{ number_format($item->tax, 2) }}</td>
                            <td align="center">S/. {{ number_format($item->total, 2) }}</td>
                            <td align="center">{{ $item->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @elseif($reportType === 'purchases')
                        <tr>
                            <td>{{ $item->purchase_number }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->supplier->name }}</td>
                            <td align="center">S/. {{ number_format($item->total, 2) }}</td>
                            <td align="center">{{ $item->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @elseif($reportType === 'products')
                        <tr>
                            <td style="text-align: left;">{{ $item->name }}</td>
                            <td>{{ $item->category->name }}</td>
                            <td align="center">{{ $item->stock }}</td>
                            <td align="center">{{ $item->stock_minimum }}</td>
                            <td align="center">S/. {{ number_format($item->purchase_price, 2) }}</td>
                            <td align="center">S/. {{ number_format($item->sales_price, 2) }}</td>
                            <td align="center">{{ ucfirst($item->status) }}</td>
                        </tr>
                    @elseif($reportType === 'inventory')
                      
                          <tr>
                              <td>{{ $item->name }}</td>
                             {{-- // <td>{{ $item->category->name }}</td> --}}
                              <td align="center">{{ $item->stock }}</td>
                              <td align="center">{{ $item->stock_minimum }}</td>
                              <td align="center">{{ ucfirst($item->stock_status) }}</td>
                          </tr>                     
                 
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

</x-app>
