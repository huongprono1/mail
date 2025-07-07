<x-dropdown align="right" width="30">
    <x-slot name="trigger">
        <span class="inline-flex rounded-md">
            <button type="button"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-black dark:text-slate-400 hover:text-gray-600 dark:hover:text-slate-300 focus:outline-none focus:bg-gray-200 dark:focus:bg-gray-800 transition ease-in-out duration-150">
                @php
                    $currentLocale = Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
                    $currentLocaleName = Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocaleNative();
                @endphp
                <img src="{{asset("flags/{$currentLocale}.svg")}}" alt="{{$currentLocaleName}}" class="size-4 me-2">
                {{$currentLocaleName}}
            </button>
        </span>
    </x-slot>

    <x-slot name="content">
        <div class="w-full">
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <x-dropdown-link rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                    class="flex text-slate-900 dark:text-slate-100">
                        <img src="{{asset("flags/{$localeCode}.svg")}}" alt="{{$localeCode}}" class="size-4 me-2"> {{ $properties['native'] }}
                </x-dropdown-link>
            @endforeach
        </div>
    </x-slot>
</x-dropdown>
