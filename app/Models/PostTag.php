<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PostTag
 *
 * @property int $id
 * @property int $post_id
 * @property int $tag_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class PostTag extends Model
{
    use HasFactory;

    protected $table = 'post_tag';

    protected $fillable = [
        'post_id',
        'tag_id',
    ];
}
