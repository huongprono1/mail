<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $title
 * @property string $content
 * @property string $slug
 * @property bool $active
 */
class Page extends Model
{
    use HasTranslations;

    protected $table = 'pages';

    protected $fillable = [
        'title',
        'content',
        'slug',
        'active',
    ];

    public $translatable = [
        'title',
        'content',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the options for generating the slug.
     *
     * @property string|null $slug
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getContentMarkdownAttribute(): string
    {
        return Str::markdown($this->content);
    }
}
