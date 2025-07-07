@if(!\Illuminate\Support\Facades\Route::is('filament.app.auth.*'))
<footer
    class="w-full sticky mt-6 bg-white px-4 py-2 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 md:px-6 lg:px-8">
    <div class="mx-auto w-full flex flex-col lg:flex-row container items-center justify-between py-2 gap-4 text-sm">
        <div class="sm:text-center">
            &copy; {{date('Y')}} <a href="{{\App\Filament\App\Pages\Home::getRoutePath()}}"
                                    class="hover:underline">{{config('app.name')}}</a>. All Rights Reserved.
        </div>
        <div class="text-center lg:text-right">
            @if($total = count($siteSetting->footer_menus))
                <div class="list-none flex flex-wrap items-center">
                    @foreach($siteSetting->footer_menus as $idx => $menu)
                        <li>
                            <a class="hover:underline me-4 md:me-6"
                               href="{{ $menu['url'] }}" wire:navigate
                               @if(isset($menu['new_tab']) && $menu['new_tab']) target="_blank" @endif>
                                {{ $menu['label'] }}
                            </a>
                        </li>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</footer>

<x-filament::modal
    alignment="center"
    icon="heroicon-o-exclamation-triangle"
    icon-color="danger"
    id="adblock-detect"
    :close-by-clicking-away="false"
    :close-by-escaping="false"
    :close-button="false"
>
    <x-slot name="heading">
        {{__('We detected that you\'re using Adblock.')}}
    </x-slot>

    <div class="text-center">
        {{__('This website relies on ads to keep running. Please disable Adblock and reload the page to continue using the service.')}}
    </div>
    {{-- Modal content --}}
</x-filament::modal>

@livewire('components.upgrade-modal')
@endif
