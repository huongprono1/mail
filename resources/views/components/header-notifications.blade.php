{{--<div class="md:hidden">--}}
{{--    <x-filament::card>--}}
{{--        <p class="mb-2">{{__('Join telegram group for support, bug report & updates')}}</p>--}}
{{--        <x-filament::button tag="a" href="https://t.me/tempmail_group" target="_blank" icon="fab-telegram">Telegram</x-filament::button>--}}
{{--    </x-filament::card>--}}
{{--</div>--}}
{{--@if($adblockDetected)--}}
{{--    <x-alert :title="__('We detected that you\'re using Adblock.')" color="danger" :icon="true">--}}
{{--        {{__('This website relies on ads to keep running. Please disable Adblock and reload the page to continue using the service.')}}--}}
{{--    </x-alert>--}}
{{--@endif--}}

@auth
    @unless(auth()->user()->hasVerifiedEmail())
        @livewire('components.email-verification-alert')
    @endunless
@endauth
