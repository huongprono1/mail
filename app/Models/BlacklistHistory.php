<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlacklistHistory extends Model
{
    protected $table = 'blacklist_histories';

    protected $fillable = [
        'blacklist_id',
        'content',
    ];

    public function blacklist(): BelongsTo
    {
        return $this->belongsTo(Blacklist::class);
    }
}
