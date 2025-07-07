<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="fi min-h-screen">
<head>
    <script>
        const theme = localStorage.getItem('theme') ?? 'system'
        if (
            theme === 'dark' ||
            (theme === 'system' &&
                window.matchMedia('(prefers-color-scheme: dark)')
                    .matches)
        ) {
            document.documentElement.classList.add('dark')
        }
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.bunny.net/css?family=inter:200,300,400,500,700,900" as="style"
          onload="this.rel='stylesheet'">
    @vite(['resources/css/app.css', 'resources/css/filament/app/theme.css'])
    @filamentStyles
{{--    @livewireStyles--}}
    <style>
        :root {
            --font-family: 'Inter';
            --sidebar-width: 20rem;
            --collapsed-sidebar-width: 4.5rem;
            --default-theme-mode: system;
        }
    </style>
</head>
<body class="fi-body fi-panel-app min-h-screen bg-gray-50 font-normal text-gray-950 antialiased dark:bg-gray-950 dark:text-white">
{{ $slot }}
@livewireScripts
@filamentScripts()
</body>
</html>
