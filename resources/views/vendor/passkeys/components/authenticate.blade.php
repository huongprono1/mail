<div>
    @include('passkeys::components.partials.authenticateScript')

    <form id="passkey-login-form" method="POST" action="{{ route('passkeys.login') }}">
        @csrf
    </form>

    @if($message = session()->get('authenticatePasskey::message'))
        <div class="bg-red-100 text-red-700 p-4 border border-red-400 rounded">
            {{ $message }}
        </div>
    @endif

    <x-filament::button type="button" onclick="authenticateWithPasskey()" class="w-full"
                        icon="heroicon-o-finger-print">
        @if ($slot->isEmpty())
            {{ __('passkeys::passkeys.authenticate_using_passkey') }}
        @else
            {{ $slot }}
        @endif
    </x-filament::button>
</div>
