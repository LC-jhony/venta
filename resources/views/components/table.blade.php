<table class="w-full border-separate border-spacing-0 text-sm font-medium rounded-lg">
    <thead class="sticky bg-gray-50 dark:bg-gray-800">
        <tr>
            {{ $head }}
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 pt-16 dark:divide-gray-700">
        {{ $slot }}
    </tbody>
</table>
