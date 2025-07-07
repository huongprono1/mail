<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ContentBlock extends Model
{
    use HasTranslations;

    protected $table = 'content_blocks';

    protected $fillable = [
        'name',
        'content',
    ];

    public $translatable = [
        'content',
    ];
}
