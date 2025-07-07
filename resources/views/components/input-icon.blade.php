{{-- File: resources/views/components/input-with-icon.blade.php --}}

@props([
    'label' => null,
    'type' => 'text',
    'name' => null,
    'id' => null,
    'placeholder' => '',
    'value' => '',
    'icon' => null,
    'iconRight' => null,
    'required' => false,
    'class' => ''
])
@isset($label)
    <label for="{{$id ?? $name}}"
           class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{$label}}</label>
@endisset
<div class="relative">
    @isset($icon)
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            @if(!\Illuminate\Support\Str::startsWith('<', $icon))
                @svg($icon, 'size-4')
            @else
                {!! $icon !!}
            @endif
        </div>
    @endisset
    <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $id ?? $name }}"
            class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full @isset($icon) ps-10 @endisset @isset($iconRight) pe-10 @endisset py-2 hover:ring-gray-300 dark:hover:ring-gray-600 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:highlight-white/5 {{$class}}"
            placeholder="{{ $placeholder }}"
            value="{{ $value }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes }}
    />
    @isset($iconRight)
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            @if(!\Illuminate\Support\Str::startsWith($iconRight, '<'))
                @svg($iconRight, 'size-5')
            @else
                {!! $iconRight !!}
            @endif
        </div>
    @endisset
</div>
