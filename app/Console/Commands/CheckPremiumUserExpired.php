<?php

namespace App\Console\Commands;

use App\Enums\UserPlanStatus;
use App\Models\UserPlan;
use BezhanSalleh\FilamentExceptions\FilamentExceptions;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckPremiumUserExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:premium:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check premium user expired and deactivate domains';

    /**
     * Execute the console command.
     *
     * @throws \Throwable
     */
    public function handle()
    {
        // search users with active plan

        $userPlans = UserPlan::with(['user', 'plan'])
            ->where('status', UserPlanStatus::Active)
            ->whereRelation('plan', 'name->en', 'Premium')
            ->whereNotNull('expired_at')
            ->get();

        $this->info('Found ' . $userPlans->count() . ' users with active plan');

        foreach ($userPlans as $userPlan) {
            if ($userPlan->expired_at < now()) {
                try {
                    DB::transaction(function () use ($userPlan) {
                        // set expired
                        $userPlan->update(['status' => UserPlanStatus::Expired]);
                        // deactivate domains skip active first cause have many.
                        $userPlan->user->domains()
                            ->where('id', '!=', $userPlan->user->domains->first()?->id)
                            ->where('is_active', true)
                            ->update(['is_active' => false]);
                        $this->info('Deactivate domains for user ' . $userPlan->user->email);
                    });
                } catch (\Throwable $e) {
                    FilamentExceptions::report($e);
                }
            }
        }

    }
}
