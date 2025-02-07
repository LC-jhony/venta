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

        div {
            page-break-inside: avoid;
            /* margin-bottom: 10px; */
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
    </style>
</head>

<body>

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
                    <img class='logo' alt='Logo'
                        src='data:image/png;base64,{{ base64_encode(file_get_contents(asset('img/logo-dark.png'))) }}'>
                    <p>Company Address,</p>
                    <p>Lorem, ipsum Dolor,</p>
                    <p>845xx145</p>
                </td>
            </tr>
        </table>
    </header>
    <main>
        {{-- @for ($i = 1; $i <= 100; $i++)
            <div>Elemento {{ $i }}</div>
        @endfor --}}
        <div>
            {{ $slot }}
        </div>
    </main>

</body>

</html>
