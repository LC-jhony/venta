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
        <table style="width: 100%; margin-top: -2.5rem; margin-bottom: 1.5rem; border-collapse: collapse;">
            <tr>
                <!-- Columna Izquierda: Título "Invoice" -->
                <td style="width: 60%; text-align: left;">
                    <h4
                        style="font-size: 2.5rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; margin: 0;">
                        Cotización
                    </h4>
                </td>

                <!-- Columna Derecha: Invoice # y Fecha -->
                <td style="width: 40%; text-align: right; vertical-align: top;">
                    <div style="border: 1px solid #ccc; border-radius: 8px; padding: 10px; background-color: #f9f9f9;">
                    <strong style="font-size: 16px; font-weight: 600; margin: 0;">
                        N°
                        <span style="padding-left: 0.5rem; font-size:14px;">{{ $quote->number_quote }}</span>
                    </strong>
                    <p style="font-size: 16px; font-weight: 600; margin: 0;">
                        Fecha: <span
                            style="padding-left: 0.5rem; font-size:14px;">{{ $quote->created_at->format('Y-m-d') }}</span>
                    </p>
                    <p style="font-size: 16px; font-weight: 600; margin: 0;">
                        Fecha: <span
                            style="padding-left: 0.5rem; font-size:14px;">{{ $quote->valid_date }}</span>
                    </p>
                </div>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <table width="100%" style="border-collapse: collapse;">
            <thead class="head" style="background-color: lightgray;">
                <tr>
                    <th>#</th>
                    <th style="text-align: left;">Description</th>
                    <th>Quantity</th>
                    <th>Unit Price $</th>
                    <th>Total $</th>
                </tr>
            </thead>
            <tbody class="body">
                 @foreach ($quote->quoteProducts as $index => $item)
                    <tr>
                        <td scope="row">{{ $index + 1 }}</td>
                        <td style="text-align: left;">{{ $item->product->name }}</td>
                        <td align="center">{{ $item->quantity }}</td>
                        <td align="right">{{ $item->price_unit }}</td>
                        <td align="right">{{ $item->total_price }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>

                <tr>
                    <td colspan="3"></td>
                    <td align="right">Total $</td>
                    <td align="right" class="gray">
                        {{ number_format($quote->total, 2, ',', '.') }}
                    </td> 
                </tr>
            </tfoot>
        </table>

    </div>
</x-app>
