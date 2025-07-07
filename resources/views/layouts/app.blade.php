<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" src="{{asset('favicon.png')}}" type="image/png"/>

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.bunny.net/css?family=inter:200,300,400,500,700,900" as="style"
          onload="this.rel='stylesheet'">

    <!-- Scripts -->
    @vite('resources/css/filament/app/theme.css')
    @vite('resources/css/app.css')
    <!-- filamentStyles -->
    @filamentStyles
    <!-- livewireStyles -->
    @livewireStyles
</head>
<body
    class="flex flex-col font-sans fi-body min-h-screen bg-gray-50 font-normal text-gray-950 antialiased dark:bg-gray-950 dark:text-white"
    x-data="{theme:null}">
@livewire('navigation-menu')
<!-- Page Heading -->
<!-- Page Content -->
<main class="grow mx-auto container">
    {{ $slot }}
</main>

@include('components.footer')

@stack('modals')

@livewire('notifications')
@filamentScripts
@livewireScriptConfig
@vite('resources/js/app.js')
</body>
</html>
