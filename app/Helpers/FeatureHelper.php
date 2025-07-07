<?php

use App\Services\UserFeatureService;

if (!function_exists('user_has_feature')) {
    function user_has_feature(string $featureSlug, ?App\Models\User $user = null): bool
    {
        $currentUser = $user ?? auth()->user();
        if (!$currentUser) {
            return false;
        }

        return (new UserFeatureService($currentUser))->hasFeature($featureSlug);
    }
}

if (!function_exists('get_user_features')) {
    function get_user_features(?App\Models\User $user = null): Illuminate\Support\Collection
    {
        if (!$user && !auth()->check()) {
            return collect();
        }
        $currentUser = $user ?? auth()->user();

        return (new UserFeatureService($currentUser))->getFeatures();
    }
}
