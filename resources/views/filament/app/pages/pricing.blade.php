<x-filament-panels::page>
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">{{__('Cheap, Transparent Pricing')}}</h1>
            <p class="text-xl text-gray-500">{{__('Choose the plan that works best for you')}}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($this->plans as $plan)
                <x-plan :plan="$plan" :is-popular="strtolower($plan->getTranslation('name', 'en')) == 'pro'"/>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
