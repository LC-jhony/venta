@props(['align' => 'left'])
@php
    $textAlignClass =
        [
            'left' => 'text-left',
            'right' => 'text-right',
            'center' => 'text-center',
        ][$align] ?? 'text-left';

@endphp
<td
    {{ $attributes->merge([
        'class' => "px-1 py-3 bg-[#F6F5F3] whitespace-nowrap md:px-6 $textAlignClass md:py-3 md:text-left font-light text-sm border border-gray-200 dark:border-white/5 dark:bg-gray-800",
    ]) }}>
    {{ $slot }}
</td>
