@props(['align' => 'left'])
@php
    $textAlignClass =
        [
            'left' => 'text-left',
            'right' => 'text-right',
            'center' => 'text-center',
        ][$align] ?? 'text-left';
@endphp
<th
    {{ $attributes->merge([
        'class' => "px-1 py-6 whitespace-nowrap bg-gray-300 $textAlignClass md:px-6 md:py-3 md:text-left font-medium text-sm border border-gray-300 dark:border-white/5 dark:bg-gray-900",
    ]) }}>
    {{ $slot }}
</th>
