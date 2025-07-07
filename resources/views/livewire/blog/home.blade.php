<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($posts as $post)
            <x-filament::section :compact="true">
                <x-slot:heading>
                    <a href="{{\App\Filament\App\Pages\ReadBlog::getUrl(['slug' => $post->slug])}}"
                       title="{{$post->title}}">
                        {{ $post->title }}
                    </a>
                </x-slot:heading>
                <x-slot:description>
                    <div class="flex items-center justify-between text-sm text-gray-400">
                        <span class="flex items-center gap-1"><x-heroicon-o-calendar-date-range
                                class="w-4 h-4 inline-flex"/> {{$post->published_at?->diffForHumans()}}</span>
                        <span class="flex items-center gap-1"><x-heroicon-o-eye class="w-4 h-4 inline-flex"/> {{Number::abbreviate($post->views)}}</span>
                    </div>
                </x-slot:description>
                <a href="{{\App\Filament\App\Pages\ReadBlog::getUrl(['slug' => $post->slug])}}"
                   title="{{$post->title}}">
                    <img src="{{$post->image?->coverImage}}" alt="{{$post->title}}" class="w-full rounded-lg"/>
                </a>
            </x-filament::section>

            <div class="my-4">
                <x-filament::pagination :paginator="$posts"/>
            </div>
        @empty
            Chưa có bài viết
        @endforelse
    </div>
</div>
