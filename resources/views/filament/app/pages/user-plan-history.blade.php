<x-filament-panels::page>
    @if($this->currentPlan)
        <x-filament::section>
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-300">{{__('Your Current Plan')}}</h1>
                <p class="text-gray-500">{{__('Plan details and subscription information')}}</p>
            </div>

            <div>
                <!-- Horizontal layout for plan information -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-filament::card class="text-center p-4 border border-gray-100 rounded-lg bg-gray-50">
                        <p class="text-gray-400 font-medium mb-1">{{__('Plan')}}</p>
                        <p class="text-gray-800 dark:text-gray-300 font-bold text-xl">{{$this->currentPlan->plan->name}}</p>
                    </x-filament::card>

                    <x-filament::card class="text-center p-4 border border-gray-100 rounded-lg bg-gray-50">
                        <p class="text-gray-400 font-medium mb-1">{{__('Started At')}}</p>
                        <p class="text-gray-800 dark:text-gray-300 text-lg">{{$this->currentPlan->started_at}}</p>
                    </x-filament::card>

                    <x-filament::card class="text-center p-4 border border-gray-100 rounded-lg bg-gray-50">
                        <p class="text-gray-400 font-medium mb-1">{{__('Expired At')}}</p>
                        <p class="text-gray-800 dark:text-gray-300 text-lg">{{$this->currentPlan->expired_at}}</p>
                    </x-filament::card>
                </div>
            </div>
        </x-filament::section>
    @endif
    {{$this->table}}
</x-filament-panels::page>
