<?php

namespace App\Models;

use App\Observers\BlacklistObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

#[ObservedBy(BlacklistObserver::class)]
class Blacklist extends Model
{
    protected $fillable = ['value', 'type', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public static function getBlockedItems(): array
    {
        return Cache::rememberForever('blacklist_items', function () {
            return self::query()->where('active', true)
                ->get()
                ->groupBy('type')
                ->toArray();
        });
    }

    public function histories(): HasMany
    {
        return $this->hasMany(BlacklistHistory::class);
    }
}
