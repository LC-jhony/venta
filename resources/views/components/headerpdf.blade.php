<div>
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

        </table></div>