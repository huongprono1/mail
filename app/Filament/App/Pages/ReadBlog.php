<?php

namespace App\Filament\App\Pages;

use App\Models\Post;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ReadBlog extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.blog.view';

    protected static bool $shouldRegisterNavigation = false;

    protected ?string $heading = '';

    protected ?string $pageTitle = 'Read Page';

    protected static ?string $slug = '/blog/{slug}';

    public Post $post;

    public function getTitle(): string|Htmlable
    {
        return $this->pageTitle;
    }

    public function mount($slug = null): void
    {
        $this->post = Post::query()->where('slug', $slug)?->first() ?? abort(404);
        $this->pageTitle = $this->post->title ?? '';

        // increment views if not has session this post
        if (! session()->has('viewed_post_'.$this->post->id)) {
            $this->post->increment('views');
            session()->put('viewed_post_'.$this->post->id, true);
        }

        SEOMeta::setDescription($this->post->meta_description);
        SEOMeta::setKeywords($this->post->meta_keywords);
        OpenGraph::addImage($this->post->cover_image);
    }
}
