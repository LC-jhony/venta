<x-filament-panels::page>
    {{-- {{ $quote }} --}}
    <div style="margin-left: auto; margin-right: auto;">
        <div
            style="border-top-left-radius: 1.5rem; border-top-right-radius: 1.5rem; background-color: white; padding: 2.5rem;">
            <div
                style="margin-top: 2.5rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.5rem;">
                <div>
                    <span style="font-size: 1.125rem; font-weight: 700;">Bill to:</span>
                    <h4 style="font-size: 1rem; font-weight: 700;">Dwyane Clark</h4>
                    <p
                        style="margin-top: 0.25rem; margin-bottom: 0.25rem; font-size: 0.875rem; font-weight: 500; letter-spacing: 0.1em;">
                        24 Dummy Street Area,</p>
                    <p
                        style="margin-top: 0.25rem; margin-bottom: 0.25rem; font-size: 0.875rem; font-weight: 500; letter-spacing: 0.1em;">
                        Location, Lorem ipsum,</p>
                    <p
                        style="margin-top: 0.25rem; margin-bottom: 0.25rem; font-size: 0.875rem; font-weight: 500; letter-spacing: 0.1em;">
                        570xx59x</p>
                </div>
                <div style="border-left: 1px solid #e5e7eb; padding-left: 2rem;">
                    <img src="{{ asset('img/logo-dark.png') }}" alt="">
                    <p style="margin-top: 0.75rem; font-size: 0.875rem; font-weight: 500; letter-spacing: 0.1em;">
                        Company Address,</p>
                    <p style="font-size: 0.875rem; font-weight: 500;">Lorem, ipsum Dolor,</p>
                    <p style="font-size: 0.875rem; font-weight: 500;">845xx145</p>
                </div>
            </div>

            <div
                style="margin-top: 2.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                <h4 style="font-size: 3.5rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;">
                    Invoice</h4>
                <div>
                    <p style="font-size: 1rem; font-weight: 600;">Invoice # <span
                            style="padding-left: 2.5rem; font-size: 0.875rem;">24856</span></p>
                    <p style="font-size: 1rem; font-weight: 600;">Date: <span
                            style="padding-left: 2.5rem; font-size: 0.875rem;">01/02/2020</span></p>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table
                    style="margin: 2.5rem auto; width: 100%; border-collapse: collapse; white-space: normal; font-size: 0.875rem; table-layout: fixed;">
                    <thead>
                        <tr style="background-color: #f8fafc;">
                            <th
                                style="border: 1px solid #e5e7eb; border-right: 0; padding: 1.25rem; text-align: left; font-size: 1.125rem; font-weight: 500; text-transform: uppercase; width: 40%;">
                                Product Description</th>
                            <th
                                style="border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; padding: 1.25rem; font-size: 1.125rem; font-weight: 500; text-transform: uppercase; width: 20%;">
                                Price</th>
                            <th
                                style="border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; padding: 1.25rem; font-size: 1.125rem; font-weight: 500; text-transform: uppercase; width: 15%;">
                                Qty</th>
                            <th
                                style="border: 1px solid #e5e7eb; border-left: 0; padding: 1.25rem; font-size: 1.125rem; font-weight: 500; text-transform: uppercase; width: 25%;">
                                Total</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: white;">
                        <tr>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; font-size: 1rem; font-weight: 500; word-wrap: break-word;">
                                Lorem ipsum Dolor</td>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; text-align: center; font-size: 1rem; font-weight: 500;">
                                $50.00</td>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; text-align: center; font-size: 1rem; font-weight: 500;">
                                5</td>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; text-align: center; font-size: 1rem; font-weight: 500;">
                                $250.00</td>
                        </tr>
                        <tr>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; font-size: 1rem; font-weight: 500; word-wrap: break-word;">
                                Pellentesque id neque ligula</td>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; text-align: center; font-size: 1rem; font-weight: 500;">
                                $10.00</td>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; text-align: center; font-size: 1rem; font-weight: 500;">
                                1</td>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; text-align: center; font-size: 1rem; font-weight: 500;">
                                $10.00</td>
                        </tr>
                        <tr>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; font-size: 1rem; font-weight: 500; word-wrap: break-word;">
                                Interdum et malesuada Fames</td>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; text-align: center; font-size: 1rem; font-weight: 500;">
                                $25.00</td>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; text-align: center; font-size: 1rem; font-weight: 500;">
                                3</td>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; text-align: center; font-size: 1rem; font-weight: 500;">
                                $75.00</td>
                        </tr>
                        <tr>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; font-size: 1rem; font-weight: 500; word-wrap: break-word;">
                                Vivamus volutpat Faucibus</td>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; text-align: center; font-size: 1rem; font-weight: 500;">
                                $40.00</td>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; text-align: center; font-size: 1rem; font-weight: 500;">
                                2</td>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; text-align: center; font-size: 1rem; font-weight: 500;">
                                $80.00</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #e5e7eb; padding: 2rem; font-size: 1rem; font-weight: 500;">
                            </td>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 2rem; text-align: center; font-size: 1rem; font-weight: 500;">
                            </td>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 2rem; text-align: center; font-size: 1rem; font-weight: 500;">
                            </td>
                            <td
                                style="border: 1px solid #e5e7eb; padding: 2rem; text-align: center; font-size: 1rem; font-weight: 500;">
                            </td>
                        </tr>
                        <tr style="background-color: #f8fafc;">
                            <td colspan="4"
                                style="border: 1px solid #e5e7eb; padding: 0.75rem 1.5rem; font-size: 1rem; font-weight: 500;">
                                comments</td>
                        </tr>
                        <tr>
                            <td colspan="3" rowspan="2"
                                style="border: 1px solid #e5e7eb; border-right: 0; padding: 1.5rem; font-size: 0.875rem; font-weight: 500;">
                                Payment is due max 7 days after <br>invoice without deduction. <br>Your bank and
                                other datails space here.</td>
                            <td
                                style="border: 1px solid #e5e7eb; background-color: #f8fafc; padding: 1.5rem; text-align: center; font-size: 1rem; font-weight: 500;">
                                <b>Subtotal:</b> $3150.00
                            </td>
                        </tr>
                        <tr>
                            <td
                                style="border: 1px solid #e5e7eb; background-color: #f8fafc; padding: 1.5rem; text-align: center; font-size: 1rem; font-weight: 500;">
                                <b>Discount:</b> $00.00
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"
                                style="border: 1px solid #e5e7eb; padding: 1.5rem; text-align: right; font-size: 1rem; font-weight: 500;">
                                <b>Total:</b> $405.00
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="background-color: #3b82f6; padding: 0.25rem;"></div>
        <div
            style="border-bottom-left-radius: 1.5rem; border-bottom-right-radius: 1.5rem; background-color: #1e3a8a; padding: 1.75rem;">
        </div>
    </div>

</x-filament-panels::page>
