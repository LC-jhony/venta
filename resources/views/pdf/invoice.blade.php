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
                    <p style="font-size: 1rem; font-weight: 600; margin: 0;">
                        Invoice # <span style="padding-left: 2.5rem; font-size: 0.875rem;">24856</span>
                    </p>
                    <p style="font-size: 1rem; font-weight: 600; margin: 0;">
                        Date: <span style="padding-left: 2.5rem; font-size: 0.875rem;">01/02/2020</span>
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
                    <th>Product Description</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            {{ $quote }}
            <tbody>
                @foreach ($quote->detailQuote as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->price_unit }}</td>
                        <td>{{ $item->total_price }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="3"></td>
                    <td style="text-align: right;  background-color: #f3f3f3;">
                        <strong>Total:</strong>
                    </td>
                    <td>$315.00</td>
                </tr>

            </tbody>
        </table>
    </div>
</x-app>
