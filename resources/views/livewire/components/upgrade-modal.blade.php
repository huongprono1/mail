<x-filament::modal id="upgrade-modal" :sticky-header="true" width="md">
    <h3 class="text-4xl font-extrabold text-center">{{$plan->name}}</h3>
    <div class="mt-2 text-center">
        <span class="text-4xl font-bold">{{ \Illuminate\Support\Number::currency($price, $plan->currency) }}</span>
        <span class="text-gray-500 ml-1">/{{$billingCycle == 'monthly' ? __('month') : __('year')}}</span>
    </div>
    <div class="columns-1 fi-fo-toggle-buttons gap-3 flex flex-wrap justify-center">
        <div>
            <input id="monthly" name="billingCycle" type="radio" value="monthly" wire:loading.attr="disabled"
                   wire:model.live="billingCycle" class="peer pointer-events-none absolute opacity-0">
            <label style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                   class="min-w-48 fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg cursor-pointer fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 [input:checked+&amp;]:bg-custom-600 [input:checked+&amp;]:text-white [input:checked+&amp;]:ring-0 [input:checked+&amp;]:hover:bg-custom-500 dark:[input:checked+&amp;]:bg-custom-500 dark:[input:checked+&amp;]:hover:bg-custom-400 [input:checked:focus-visible+&amp;]:ring-custom-500/50 dark:[input:checked:focus-visible+&amp;]:ring-custom-400/50 [input:focus-visible+&amp;]:z-10 [input:focus-visible+&amp;]:ring-2 [input:focus-visible+&amp;]:ring-gray-950/10 dark:[input:focus-visible+&amp;]:ring-white/20"
                   for="monthly">
                <span class="fi-btn-label">
                    {{__('Monthly')}}
                </span>
            </label>
        </div>
        <div class="relative">
            <input id="yearly" name="billingCycle" type="radio" value="yearly" wire:loading.attr="disabled"
                   wire:model.live="billingCycle" class="peer pointer-events-none absolute opacity-0">
            <label style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                   class="min-w-48 fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg cursor-pointer fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 [input:checked+&amp;]:bg-custom-600 [input:checked+&amp;]:text-white [input:checked+&amp;]:ring-0 [input:checked+&amp;]:hover:bg-custom-500 dark:[input:checked+&amp;]:bg-custom-500 dark:[input:checked+&amp;]:hover:bg-custom-400 [input:checked:focus-visible+&amp;]:ring-custom-500/50 dark:[input:checked:focus-visible+&amp;]:ring-custom-400/50 [input:focus-visible+&amp;]:z-10 [input:focus-visible+&amp;]:ring-2 [input:focus-visible+&amp;]:ring-gray-950/10 dark:[input:focus-visible+&amp;]:ring-white/20"
                   for="yearly">
                    <span class="fi-btn-label">
                        {{__('Yearly')}}
                    </span>
                <span style="--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);"
                      class="absolute -top-3 -right-3 fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-1 py-0.5 min-w-[theme(spacing.6)] fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400 dark:text-custom-400 dark:ring-custom-400/30 fi-color-danger">
                  {{__('Free 2 Month')}}
                </span>
            </label>
        </div>
    </div>

    <div class="mt-4">
        <ul class="space-y-4">
            @foreach($plan->planFeatures as $feature)
                <li class="flex items-start">
                    @if((is_numeric($feature->value) && $feature->value > 0 ) || !is_numeric($feature->value) && $feature->value != '')
                        <svg class="h-6 w-6 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 13l4 4L19 7"></path>
                        </svg>
                    @else
                        <svg class="h-6 w-6 text-gray-300 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    @endif
                    <div class="text-gray-400 flex items-center justify-between w-full">
                        {{$feature->feature->name}}
                        @if(is_numeric($feature->value))
                            <x-filament::badge size="sm">
                                {{(string)$feature->value}}
                            </x-filament::badge>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
        <x-slot name="footer">
            <x-filament::button
                wire:loading.attr="disabled"
                wire:click="subscribe"
                size="xl" class="block w-full">{{__('Get Started')}}</x-filament::button>
        </x-slot>
    </div>
</x-filament::modal>
