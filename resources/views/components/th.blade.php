@props(['align' => 'left'])
@php
    $textAlignClass =
        [
            'left' => 'text-left',
            'right' => 'text-right',
            'center' => 'text-center',
        ][$align] ?? 'text-left';
@endphp
<th {{ $attributes->merge([
    'class' => "fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 font-medium text-sm text-gray-900 dark:text-white $textAlignClass"
]) }}>
    <span class="flex items-center gap-x-1">
        {{ $slot }}
    </span>
</th>
