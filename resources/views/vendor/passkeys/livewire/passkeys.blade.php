<div>
    <div>
        <form id="passkeyForm" wire:submit="validatePasskeyProperties">
            <div class="grid grid-cols-[--cols-default] fi-fo-component-ctn gap-6">
                <x-filament::input.wrapper>
                    <x-filament::input autocomplete="off" type="text" wire:model="name"
                                       placeholder="{{__('Passkey name')}}"/>
                </x-filament::input.wrapper>
                @error('name')
                <span
                    class="fi-fo-field-wrp-error-message text-sm text-danger-600 dark:text-danger-400">{{ $message }}</span>
                @enderror
            </div>
            <div class="text-right mt-4">
                <x-filament::button type="submit">
                    {{ __('Create') }}
                </x-filament::button>
            </div>
        </form>
    </div>

    <div class="mt-6">
        <ul>
            @foreach($passkeys as $passkey)
                <li class="flex justify-between">
                    <div class="font-semibold">
                        {{ $passkey->name }}
                    </div>
                    <div class="ml-2">
                        {{ __('passkeys::passkeys.last_used') }}
                        : {{ $passkey->last_used_at?->diffForHumans() ?? __('passkeys::passkeys.not_used_yet') }}
                    </div>
                    <x-filament::button size="sm" wire:click="deletePasskey({{ $passkey->id }})" color="danger">
                        {{ __('passkeys::passkeys.delete') }}
                    </x-filament::button>
                </li>
            @endforeach
        </ul>
    </div>
</div>

@include('passkeys::livewire.partials.createScript')
