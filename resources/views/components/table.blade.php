<div class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1
 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
    <div class="overflow-x-auto">
        <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
            <thead class="bg-gray-50 dark:bg-white/5">
                <tr class="border-b border-gray-200 dark:border-white/5">
                    {{ $head }}
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
