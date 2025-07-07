
    <x-filament::dropdown teleport="true" placement="bottom-end">
        <x-slot name="trigger" class="text-gray-600 hover:text-gray-800 dark:text-gray-500 dark:hover:text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.5" stroke="currentColor" class="block h-5 w-5 dark:hidden">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"></path>
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.5" stroke="currentColor" class="hidden h-5 w-5 dark:flex">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"></path>
            </svg>
        </x-slot>

        <x-filament::dropdown.list x-data="{ theme: null }"
                                   x-init="
        $watch('theme', () => {
            $dispatch('theme-changed', theme)
        })

        theme = localStorage.getItem('theme') || 'system'    ">
            <x-filament::dropdown.list.item icon="heroicon-o-sun"
                x-on:click="(theme = 'light') && close()"
                                            x-bind:class="
        theme === 'light'            ? 'fi-active bg-gray-50 text-primary-500 dark:bg-white/5 dark:text-primary-400'
            : 'text-gray-400 hover:text-gray-500 focus-visible:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 dark:focus-visible:text-gray-400'
    "
            >
                {{__('Light')}}
            </x-filament::dropdown.list.item>

            <x-filament::dropdown.list.item icon="heroicon-o-moon"
                x-on:click="(theme = 'dark') && close()"
                                            x-bind:class="
        theme === 'dark'            ? 'fi-active bg-gray-50 text-primary-500 dark:bg-white/5 dark:text-primary-400'
            : 'text-gray-400 hover:text-gray-500 focus-visible:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 dark:focus-visible:text-gray-400'
    ">
                {{__('Dark')}}
            </x-filament::dropdown.list.item>
            <x-filament::dropdown.list.item icon="heroicon-o-computer-desktop"
                                            x-on:click="(theme = 'system') && close()"
                                            x-bind:class="
        theme === 'system'            ? 'fi-active bg-gray-50 text-primary-500 dark:bg-white/5 dark:text-primary-400'
            : 'text-gray-400 hover:text-gray-500 focus-visible:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 dark:focus-visible:text-gray-400'
    ">
                {{__('System')}}
            </x-filament::dropdown.list.item>
        </x-filament::dropdown.list>
    </x-filament::dropdown>
