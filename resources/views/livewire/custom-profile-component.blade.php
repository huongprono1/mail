<div>
    <x-filament-breezy::grid-section md=2 :title="__('Telegram Settings')"
                                     :description="__('Setting connect to Telegram')">
        <x-filament::card>
            <form wire:submit.prevent="submit" class="space-y-6">
                {{ $this->form }}
                <div class="text-right">
                    <x-filament::button type="submit" form="submit" class="align-right">
                        {{__('Update')}}
                    </x-filament::button>
                </div>
            </form>
        </x-filament::card>
    </x-filament-breezy::grid-section>
    <x-filament-breezy::grid-section md=2 :title="__('Passkey name')" :description="__('Manage your passkeys')">
        <x-filament::card>
            <livewire:passkeys/>
        </x-filament::card>
    </x-filament-breezy::grid-section>
</div>
