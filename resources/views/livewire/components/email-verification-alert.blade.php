<div>
    <x-alert color="warning" :icon="true"
             :title="__('Your email address is not verified.')">
        <div class="flex items-center gap-1 text-sm">
            {{__('Please verify your email address to continue using the service.')}}
            <x-filament::button outlined="true" wire:click="resendVerification" wire:target="resendVerification"
                                wire:loading.attr="disabled" size="sm" icon="heroicon-o-arrow-uturn-right">
                {{ __('Resend Verification Email') }}
            </x-filament::button>
        </div>
    </x-alert>
</div>
