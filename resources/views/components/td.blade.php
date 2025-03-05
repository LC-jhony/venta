@props(['align' => 'left'])
@php
    $textAlignClass =
        [
            'left' => 'text-left',
            'right' => 'text-right',
            'center' => 'text-center',
        ][$align] ?? 'text-left';
@endphp
<td {{ $attributes->merge([
    'class' => "fi-ta-cell p-0 first-of-type:ps-4 last-of-type:pe-4 sm:first-of-type:ps-6 sm:last-of-type:pe-6 before:empty:hidden after:empty:hidden $textAlignClass"
]) }}>
    <div class="flex w-full px-3 py-4">
        <div class="flex-1 w-full text-sm text-gray-500 dark:text-gray-400">
            {{ $slot }}
        </div>
    </div>
</td>
