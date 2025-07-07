<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyApiUsage extends Model
{
    protected $table = 'monthly_api_usage';

    protected int $default_limit = 3000;

    protected $fillable = [
        'user_id',
        'year',
        'month',
        'count',
        'limit',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'count' => 'integer',
        'limit' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getCurrentUsage(User $user): self
    {
        $now = now();

        return static::firstOrCreate(
            [
                'user_id' => $user->id,
                'year' => $now->year,
                'month' => $now->month,
            ],
            [
                'count' => 0,
                'limit' => (new static)->default_limit,
            ]
        );
    }

    public function incrementUsage(): void
    {
        $this->increment('count');
    }

    public function hasReachedLimit(): bool
    {
        return $this->count >= $this->user->getFeatureValue('api-limit', $this->default_limit);
    }

    public function getRemainingRequests(): int
    {
        return max(0, $this->user->getFeatureValue('api-limit', $this->default_limit) - $this->count);
    }

    public function getLimit(): int
    {
        return $this->user->getFeatureValue('api-limit', $this->default_limit);
    }

    public function getUsagePercentage(): float
    {
        $limit = $this->user->getFeatureValue('api-limit', $this->default_limit);
        if ($limit === 0) {
            return 100;
        }

        return min(100, round(($this->count / $limit) * 100, 2));
    }
}
