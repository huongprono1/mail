<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Plan
 *
 * @property int $id
 * @property string $name
 * @property string $key
 * @property string|null $description
 * @property float $price
 * @property float $month_price
 * @property float $year_price
 * @property string $currency
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Feature[] $features
 * @property-read int|null $features_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 */
class Plan extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',
        'is_active',
        'key',
        'month_price',
        'year_price',

    ];

    public $translatable = [
        'name',
        'description',
        'currency',
        'month_price',
        'year_price',
    ];

    public function planFeatures(): HasMany
    {
        return $this->hasMany(PlanFeature::class, 'plan_id', 'id');
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'plan_feature')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function users(): HasMany
    {
        return $this->hasMany(UserPlan::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', 1);
    }
}
