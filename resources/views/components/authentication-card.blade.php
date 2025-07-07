<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="flex justify-center sm:mx-auto sm:w-full sm:max-w-md">
        <x-application-mark />
    </div>
    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
        <x-card class="card-body p-6">
            {{ $slot }}
        </x-card>
    </div>
</div>
