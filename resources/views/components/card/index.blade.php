<div {{ $attributes->merge(['class' => 'rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-800 dark:ring-gray-700 overflow-hidden print:ring-0']) }}>
    @isset($header)
        <div class="px-6 py-4 font-semibold border-b dark:border-gray-700">
            {{$header}}
        </div>
    @endisset
    <div class="">
        {{ $slot }}
    </div>
    @isset($footer)
        <div class="px-6 py-4 font-semibold border-t dark:border-gray-700">
            {{$footer}}
        </div>
    @endisset
</div>
