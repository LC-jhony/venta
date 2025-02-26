<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cotización</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Cotización #{{ $quote->number_quote }}</h2>
        </div>
        
        <div class="content">
            <p>Estimado cliente,</p>
            
            <p>Adjunto encontrará la cotización solicitada con el número #{{ $quote->number_quote }}.</p>
            
            <p>Detalles de la cotización:</p>
            <ul>
                <li>Número: {{ $quote->number_quote }}</li>
                <li>Fecha: {{ $quote->created_at->format('d/m/Y') }}</li>
                <li>Válida hasta: {{ $quote->valid_date }}</li>
                <li>Total: ${{ number_format($quote->total, 2) }}</li>
            </ul>
            
            <p>Si tiene alguna pregunta o necesita más información, no dude en contactarnos.</p>
            
            <p>Saludos cordiales,</p>
            <p>{{ config('app.name') }}</p>
        </div>
        
        <div class="footer">
            <p>Este es un correo electrónico automático, por favor no responda a este mensaje.</p>
        </div>
    </div>
</body>
</html>