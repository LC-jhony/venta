<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div class="flex flex-col justify-center">
        {{-- {{ $getState() }} --}}
        {!! DNS1D::getBarcodeHTML($getState(), 'C128', 2, 50) !!}
        <div class="text-xl justify-center w-full mt-1"
        style="letter-spacing: 0.4em; ">{{ $getState() }}</div>
    </div>
</x-dynamic-component>
