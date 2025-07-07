<x-filament-panels::page>
    @livewire('temp-mail.home')
    @if(!user_has_feature('no_ads'))
        {!! setting('ads.below_form_header') !!}
    @endif
    <div class="page">
        {!! content_block('introduction') !!}
    </div>
</x-filament-panels::page>
