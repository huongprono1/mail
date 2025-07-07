<x-filament-panels::page>
    <x-alert
        :title="__('Ensure that the MX records are correctly pointed to the mail server list before proceeding with domain addition.')"
        color="info"
        :icon="true">
        <ul class="list-disc ps-6 marker:text-gray-400">
            @foreach (setting('mail.servers', []) as $domain)
                <li><code>{{ $domain }}</code></li>
            @endforeach
        </ul>
    </x-alert>
    <x-alert
        :title="__('If you are using Cloudflare, you must disable the DNS Proxy (orange cloud) for the service to function correctly.')"
        color="warning"
        :icon="true"/>
    {{ $this->table }}

    <x-filament::modal width="lg"
                       footer-actions-alignment="center"
                       id="limit-domain-modal" alignment="center" :close-button="false"
                       icon="heroicon-o-exclamation-triangle"
                       icon-color="danger">
        <x-slot name="heading">
            {{$this->modalTitle}}
        </x-slot>

        <x-slot name="description">
            {{$this->modalDescription}}
        </x-slot>

        <x-slot name="footerActions">
            <x-filament::button
                x-on:click="$dispatch('open-modal', { id: 'upgrade-modal' })"
                {{--                tag="a" href="{{\App\Filament\App\Pages\Pricing::getUrl(panel: 'app')}}"--}}
                                icon="heroicon-o-arrow-up-circle">{{__('Upgrade')}}</x-filament::button>
        </x-slot>
    </x-filament::modal>
</x-filament-panels::page>
