<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reporte - {{ ucfirst($reportType) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .date-range {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Reporte de {{ ucfirst($reportType) }}</h1>
    </div>

    <div class="date-range">
        <p>Período: {{ $startDate }} - {{ $endDate }}</p>
    </div>

    @if ($reportType === 'sales' || $reportType === 'purchases')
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>{{ $reportType === 'sales' ? 'Ventas' : 'Compras' }}</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->date }}</td>
                        <td>{{ $reportType === 'sales' ? $item->total_sales : $item->total_purchases }}</td>
                        <td>${{ number_format($item->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($reportType === 'products')
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Stock</th>
                        <th>Stock Mínimo</th>
                        <th>Precio Venta</th>
                        <th>Precio Compra</th>
                        <th>Fecha Vencimiento</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $product)
                        @php
                            $rowClass = '';
                            $status = '';

                            if ($product->stock <= 0) {
                                $rowClass = 'danger-row';
                                $status = 'Sin Stock';
                            } elseif ($product->stock <= $product->stock_minimum) {
                                $rowClass = 'warning-row';
                                $status = 'Stock Bajo';
                            }

                            if ($product->expiration) {
                                if (strtotime($product->expiration) < time()) {
                                    $rowClass = 'danger-row';
                                    $status = 'Expirado';
                                } elseif (strtotime($product->expiration) < strtotime('+30 days')) {
                                    $rowClass = 'warning-row';
                                    $status = 'Próximo a Vencer';
                                }
                            }
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->stock_minimum }}</td>
                            <td>${{ number_format($product->sales_price, 2) }}</td>
                            <td>${{ number_format($product->purchase_price, 2) }}</td>
                            <td>{{ $product->expiration ? date('Y-m-d', strtotime($product->expiration)) : 'N/A' }}
                            </td>
                            <td>{{ $status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif($reportType === 'inventory')
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->stock_minimum }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    @endif
</body>

</html>
