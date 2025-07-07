@php
    /* @var \App\models\Page $page */
@endphp

<x-app-layout title="{{ $page->title }}" wide="true">
    <x-filament::section>
        <x-slot name="heading">
            {{$page->title}}
        </x-slot>
        {!! \Filament\Support\Markdown::block($page->content) !!}
        {{-- Content --}}
    </x-filament::section>
</x-app-layout>
