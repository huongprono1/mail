<?php

namespace App\Observers;

use App\Enums\UserPlanStatus;
use App\Models\Plan;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $basicPlan = Plan::where('name->en', 'Basic')->first();
        if ($basicPlan) {
            $user->plans()->create([
                //                'user_id' => $user->id,
                'plan_id' => $basicPlan->id,
                'started_at' => now(),
                'expired_at' => null,
                'status' => UserPlanStatus::Active,
            ]);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
