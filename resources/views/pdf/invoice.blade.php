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
                    <div style="border-left: 1px solid #111;
                    ;">
                        <img class='logo' alt='Logo'
                            src='data:image/png;base64,{{ base64_encode(file_get_contents(asset('img/logo-dark.png'))) }}'>
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
                    <th>Product Description</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Lorem ipsum Dolor</td>
                    <td>$50.00</td>
                    <td>5</td>
                    <td>$250.00</td>
                </tr>
                <tr>
                    <td>Pellentesque id neque ligula</td>
                    <td>$10.00</td>
                    <td>1</td>
                    <td>$10.00</td>
                </tr>
                <tr>
                    <td>Interdum et malesuada Fames</td>
                    <td>$25.00</td>
                    <td>3</td>
                    <td>$75.00</td>
                </tr>
                <tr>
                    <td>Vivamus volutpat Faucibus</td>
                    <td>$40.00</td>
                    <td>2</td>
                    <td>$80.00</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Subtotal:</strong></td>
                    <td>$315.00</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Discount:</strong></td>
                    <td>$00.00</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                    <td>$405.00</td>
                </tr>
            </tbody>
        </table>
    </div>
</x-app>
