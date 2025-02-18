<x-app>
    <header>
        <table class="header-table">
            <tr>
                <td class="left">
                    <span>Bill to:</span>
                    <h4>Dwyane Clark</h4>
                    <p>24 Dummy Street Area,</p>
                    <p>Location, Lorem ipsum,</p>
                    <p>570xx59x</p>
                </td>
                <td class="right">
                    <div style="border-left: 1px solid #111;">
                        <img class='logo' alt='Logo'
                            src='data:image/png;base64,{{ base64_encode(file_get_contents(asset('img/logo-dark.png'), false, stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]))) }}'>
                        <p>Company Address,</p>
                        <p>Lorem, ipsum Dolor,</p>
                        <p>845xx145</p>
                    </div>
                </td>
            </tr>
        </table>
    </header>
    <div>
        <table style="width: 100%; margin-top: -2.5rem; margin-bottom: 1.5rem; border-collapse: collapse;">
            <tr>
                <!-- Columna Izquierda: Título "Invoice" -->
                <td style="width: 60%; text-align: left;">
                    <h4
                        style="font-size: 3.5rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; margin: 0;">
                        Invoice
                    </h4>
                </td>

                <!-- Columna Derecha: Invoice # y Fecha -->
                <td style="width: 40%; text-align: right; vertical-align: top;">
                    <p style="font-size: 16px; font-weight: 600; margin: 0;">
                        Invoice #
                        <span style="padding-left: 0.5rem; font-size:14px;">{{ $sale->invoice_number }}</span>
                    </p>
                    <p style="font-size: 16px; font-weight: 600; margin: 0;">
                        Date: <span
                            style="padding-left: 0.5rem; font-size:14px;">{{ $sale->created_at->format('d/m/Y') }}</span>
                    </p>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th style="text-align: left;">Product Description</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($sale->saleDetails as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="text-align: left;">{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->unit_price }}</td>
                        <td>{{ $item->total_price }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="3" rowspan="3" style="padding: 10px; border-top: 1px solid #ddd;">
                        {{ $sale->notes }}</td>
                    <td style="text-align: right; background-color: #f3f3f3; padding: 8px; font-weight: 600;">
                        Sub Total:
                    </td>
                    <td style="padding: 8px; border-top: 1px solid #ddd;">{{ $sale->subtotal, 2, ',', '.' }}</td>
                </tr>
                <tr>

                    <td style="text-align: right; background-color: #f3f3f3; padding: 8px; font-weight: 600;">
                        IGV (18%):
                    </td>
                    <td style="padding: 8px;">{{ number_format($sale->tax, 2, ',', '.') }}</td>
                </tr>
                <tr>

                    <td style="text-align: right; background-color: #f3f3f3; padding: 8px; font-weight: 600;">
                        Total:
                    </td>
                    <td style="padding: 8px; font-weight: bold;">{{ moneyFormat($sale->total, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>



    </div>
</x-app>
