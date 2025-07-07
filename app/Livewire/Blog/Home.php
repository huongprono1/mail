<?php

namespace App\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;

class Home extends Component
{
    public ?string $tag = '';

    public function mount(): void
    {
        $this->tag = request()->query('tag');
    }

    public function render()
    {
        $query = Post::published()
            ->with('tags')
            ->orderByDesc('published_at');
        if ($this->tag) {
            $query->whereHas('tags', function ($query) {
                $query->where('slug', $this->tag);
            });
        }
        $posts = $query->paginate(10);

        return view('livewire.blog.home', compact('posts'));
    }
}
