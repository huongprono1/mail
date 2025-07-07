<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Feature extends Model
{
    use HasTranslations;

    protected $fillable = [
        'key',
        'name',
        'description',
    ];

    public $translatable = [
        'name',
        'description',
    ];
    //    public function plans()
    //    {
    //        return $this->belongsToMany(Plan::class, 'plan_features')->withPivot('value');
    //    }

    public function planFeatures(): HasMany
    {
        return $this->hasMany(PlanFeature::class);
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_feature')
            ->withPivot('value')
            ->withTimestamps();
    }
}
