<x-filament-panels::page>
    @guest
        {{ $this->form }}
    @endguest
            @livewire('temp-mail.inbox', ['mailbox' => $mail])
</x-filament-panels::page>
