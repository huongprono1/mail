@foreach ($menuItems as $item)
    @php
        $target = $item['new_tab'] ? '_blank' : '';
        $url = $item['route']?route($item['route']): url($item['url']);
        $active = request()->fullUrlIs($url);
    @endphp
    <x-nav-link class="{{ $item->link_class }}"
                href="{{ $url }}"
                :active="$active"
                target="{{$target}}"
    >

        @if(isset($item['icon']) && !empty($item['icon']))
            @if($active)
                <x-icon class="fi-sidebar-item-icon w-5 h-5 text-primary-600 dark:text-primary-400" name="{{ $item['icon'] }}"></x-icon>
            @else
                <x-icon class="fi-sidebar-item-icon w-5 h-5 text-gray-700 dark:text-gray-200" name="{{ $item['icon'] }}"></x-icon>
            @endif
        @endif

            {{ $item['title'][app()->getLocale()] }}

        @if($item['has_badge'])
            <x-filament::badge :color="$item['badge_color']">
                {{ $item['badge'][app()->getLocale()] }}
            </x-filament::badge>
        @endif
    </x-nav-link>
@endforeach



