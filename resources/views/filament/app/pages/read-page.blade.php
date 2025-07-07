<x-filament-panels::page>
    <h1 class="text-4xl font-semibold">{{$this->page->title}}</h1>
    @if(!user_has_feature('no_ads'))
        {!! setting('ads.before_page_content') !!}
    @endif
    <div class="page" id="page-content">
        {!! markdown($this->page->content) !!}
    </div>
</x-filament-panels::page>
