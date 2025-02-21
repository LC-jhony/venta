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
    @php
        for ($i = 0; $i < 1000; $i++) {
            echo $i . '<br>';
        }
    @endphp
</x-app>
