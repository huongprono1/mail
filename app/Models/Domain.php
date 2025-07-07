<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static Builder active()
 * @method static Builder accessible()
 */
class Domain extends Model
{
    protected $table = 'domains';

    protected $fillable = [
        'name',
        'is_active',
        'user_id',
        'is_private',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_private' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope a query to only include accessible domains for the current user.
     *
     * This scope filters domains that are:
     * - Active (is_active = true)
     * - Either public (no user_id), or private but not restricted (is_private = false),
     *   or private and belonging to the current user (is_private = true and user_id matches).
     */
    public function scopeAccessible(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function ($query) {
                $query->where('is_private', false);
                if (auth()->check()) {
                    $query->orWhere(function ($q2) {
                        $q2->where('is_private', true)
                            ->where(function ($q3) {
                                $q3->where('user_id', auth()->id())
                                    ->orWhereNull('user_id');
                            });
                    });
                }
            });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mails(): HasMany
    {
        return $this->hasMany(Mail::class);
    }
}
