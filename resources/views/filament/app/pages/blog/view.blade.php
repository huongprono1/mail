<x-filament-panels::page>
    <x-filament::section :compact="true">
        <x-slot:heading>{{$post->title}}</x-slot:heading>
        <x-slot:description>
            <div class="flex items-center justify-between text-sm text-gray-400">
                <span class="flex items-center gap-1"><x-heroicon-o-calendar-date-range class="w-4 h-4 inline-flex"/> {{$post->published_at?->diffForHumans()}}</span>
                <span class="flex items-center gap-1"><x-heroicon-o-eye class="w-4 h-4 inline-flex"/> {{Number::abbreviate($post->views)}}</span>
            </div>
        </x-slot:description>

        <div class="page">
            {!! markdown($post->content) !!}
        </div>
        @if($post->tags)
            <div class="mt-4">
                @foreach($post->tags as $tag)
                    <a href="{{\App\Filament\App\Pages\Blog::getUrl()}}?tag={{$tag->slug}}">#{{$tag->name}}</a>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-panels::page>
