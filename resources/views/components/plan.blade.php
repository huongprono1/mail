@props([
    'isPopular' => null,
    'plan'
])

@php
    $popularClass = match ($isPopular){
        true => 'ring-2 !ring-teal-500 transform md:scale-105',
        default => ''
    }
@endphp
<x-filament::section class="rounded-lg shadow-lg overflow-hidden {{$popularClass}}">
    <x-slot:heading>
        @if($isPopular)
            <div class="text-center">
                <span
                    class="inline-block px-3 py-1 text-xs font-semibold text-teal-600 bg-teal-100 rounded-full mb-2">{{__('MOST POPULAR')}}</span>
            </div>
        @endif
        <h3 class="text-xl font-semibold text-center">{{$plan->name}}</h3>
        <div class="mt-4 text-center">
            <span class="text-4xl font-bold">{{$plan->currency}}{{$plan->price}}</span>
            <span class="text-gray-500 ml-1">/{{__('month')}}</span>
        </div>
    </x-slot:heading>
    <div class="">
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
        <div class="mt-8">
            <x-filament::button tag="a" href="#" size="xl"
                                class="block w-full">{{__('Get Started')}}</x-filament::button>
        </div>
    </div>
</x-filament::section>
