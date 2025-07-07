<x-filament::dropdown teleport="true" placement="bottom-end">
    <x-slot name="trigger" class="gap-2 items-center">
        @php
            $currentLocale = Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
//            $currentLocaleName = Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocaleNative();
        @endphp
{{--        <img src="{{asset("flags/{$currentLocale}.svg")}}" alt="{{$currentLocaleName}}" class="w-6 rounded-full">--}}
{{--        {{$currentLocaleName}}--}}
        <div class="fi-dropdown-list-item-image h-5 w-5 rounded-full bg-cover bg-center"
             style="background-image: url('{{asset("flags/{$currentLocale}.svg")}}')">
        </div>
    </x-slot>

    <x-filament::dropdown.list>
        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <x-filament::dropdown.list.item tag="a"
                                            rel="alternate"
                                            hreflang="{{ $localeCode }}"
                                            href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                            :image="asset('flags/'.$localeCode.'.svg')"
                                            :disabled="$currentLocale==$localeCode"
                                            :spa-mode="false"
            >
                {{ $properties['native'] }}
            </x-filament::dropdown.list.item>
        @endforeach
    </x-filament::dropdown.list>
</x-filament::dropdown>
