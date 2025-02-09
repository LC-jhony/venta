<x-filament-panels::page>
    {{-- {{ $quote }} --}}
    <div class="dark:bg-gray-800">
        <div
            style="border-top-left-radius: 1.5rem; border-top-right-radius: 1.5rem; background-color: var(--card-background); padding: 2.5rem;">
            <div
                style="margin-top: 2.5rem; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1.5rem;">
                <div>
                    <span style="font-size: 1.125rem; font-weight: 700; color: var(--text-color);">Bill to:</span>
                    <h4 style="font-size: 1rem; font-weight: 700; color: var(--text-color);">Dwyane Clark</h4>
                    <p
                        style="margin-top: 0.25rem; margin-bottom: 0.25rem; font-size: 0.875rem; font-weight: 500; letter-spacing: 0.1em; color: var(--text-color);">
                        24 Dummy Street Area,</p>
                    <p
                        style="margin-top: 0.25rem; margin-bottom: 0.25rem; font-size: 0.875rem; font-weight: 500; letter-spacing: 0.1em; color: var(--text-color);">
                        Location, Lorem ipsum,</p>
                    <p
                        style="margin-top: 0.25rem; margin-bottom: 0.25rem; font-size: 0.875rem; font-weight: 500; letter-spacing: 0.1em; color: var(--text-color);">
                        570xx59x</p>
                </div>
                <div style="border-left: 1px solid var(--border-color); padding-left: 2rem;">
                    <img src="{{ asset('img/logo-dark.png') }}" alt="">
                    <p
                        style="margin-top: 0.75rem; font-size: 0.875rem; font-weight: 500; letter-spacing: 0.1em; color: var(--text-color);">
                        Company Address,</p>
                    <p style="font-size: 0.875rem; font-weight: 500; color: var(--text-color);">Lorem, ipsum Dolor,</p>
                    <p style="font-size: 0.875rem; font-weight: 500; color: var(--text-color);">845xx145</p>
                </div>
            </div>

            <div
                style="margin-top: 2.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                <h4
                    style="font-size: 3.5rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-color);">
                    Invoice</h4>
                <div>
                    <p style="font-size: 1rem; font-weight: 600; color: var(--text-color);">Invoice # <span
                            style="padding-left: 2.5rem; font-size: 0.875rem;">24856</span></p>
                    <p style="font-size: 1rem; font-weight: 600; color: var(--text-color);">Date: <span
                            style="padding-left: 2.5rem; font-size: 0.875rem;">01/02/2020</span></p>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <x-table>
                    <x-slot name="head">
                        <tr>
                            <x-th>
                                Product Description</x-th>
                            <x-th>
                                Price</x-th>
                            <x-th>
                                Qty</x-th>
                            <x-th>
                                Total</x-th>
                        </tr>
                    </x-slot>
                    @foreach ($quote->detailQuote as $item)
                        <tr>
                            <x-td>
                                {{ $item->product->name }}
                            </x-td>
                            <x-td>
                                {{ $item->price_unit }} </x-td>
                            <x-td>
                                {{ $item->quantity }}</x-td>
                            <x-td>
                                {{ $item->total_price }}</x-td>
                        </tr>
                    @endforeach
                    <tr>
                        <x-td colspan="2">{{ $quote->notes }}</x-td>
                        <x-th>
                            <strong>Total:</strong>
                        </x-th>
                        <x-td>{{ number_format($quote->detailQuote->sum('total_price'), 2) }}</x-td>
                    </tr>
                </x-table>
            </div>
        </div>
        <div style="background-color: #3b82f6; padding: 0.25rem;"></div>
        <div style="background-color: #1e3a8a; padding: 1.75rem;">
        </div>
    </div>

</x-filament-panels::page>
