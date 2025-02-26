<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PDF Invoice</title>
    <style>
        @page {
            margin: 250px 50px 120px 50px;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        header {
            position: fixed;
            top: -220px;
            left: 0;
            right: 0;
            height: 120px;

            text-align: left;
            /* padding: 20px; */
            font-size: 18px;
        }

        /* Estilos para la tabla dentro del header */

        /*-----*/
        table {
            font-size: x-small;
        }

        .head th,
        .body tr td {
            padding: 4px;
            border: 1px lightgray solid;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .gray {
            background-color: lightgray
        }
    </style>
</head>

<body>
    {{ $slot }}
</body>

</html>
