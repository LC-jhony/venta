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
            font-family: Helvetica, Arial, sans-serif;
            font-size: small;
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
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            padding: 10px;
        }

        .header-table .left {
            width: 60%;
        }

        .header-table .right {
            width: 40%;
            text-align: right;
        }

        .header-table img {
            max-width: 150px;
        }

        .header-table p,
        .header-table h4,
        .header-table span {
            margin: 5px 0;
            font-size: 14px;
        }

        .header-table h4 {
            font-size: 16px;
            font-weight: bold;
        }

        .header-table span {
            font-size: 14px;
            font-weight: bold;
        }

        /*-----*/
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #a5b1c2;
            padding: 15px;
            text-align: center;
            font-size: 14px;
        }

        .invoice-table th {
            background-color: #f3f3f3;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    {{ $slot }}
</body>

</html>
