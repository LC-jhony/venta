<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        @page {
            margin: 220px 50px 120px 50px;
            /* Aumenté el margen inferior para evitar superposiciones */
        }

        header {
            position: fixed;
            top: -180px;
            left: 0;
            right: 0;
            height: 170px;
            background: #ddd;
            text-align: center;
            line-height: 200px;
            font-size: 20px;
            font-weight: bold;
        }

        /* footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: #ddd;
            text-align: center;
            line-height: 100px;
            font-size: 16px;
        } */



        /* Asegurar que los elementos no sean tapados por el footer */
        div {
            page-break-inside: avoid;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <header>
        Encabezado
    </header>

    {{-- <footer>
        Pie de página
    </footer> --}}

    <main>
        @for ($i = 1; $i <= 100; $i++)
            <div>Elemento {{ $i }}</div>
        @endfor
    </main>

</body>

</html>
