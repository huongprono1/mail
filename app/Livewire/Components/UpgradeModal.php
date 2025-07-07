<?php

namespace App\Livewire\Components;

use App\Enums\UserPlanStatus;
use App\Filament\App\Pages\Checkout;
use App\Models\Plan;
use App\Models\UserPlan;
use Livewire\Component;

class UpgradeModal extends Component
{
    public ?Plan $plan = null;

    public string $billingCycle = 'monthly';

    public float $price;

    public function updateBillingCycle($billingCycle): void
    {
        $this->billingCycle = $billingCycle;
    }

    public function subscribe()
    {
        if (auth()->guest()) {
            return redirect()->route('filament.app.auth.login');
        }
        // create user plan pending
        $userPlan = UserPlan::create([
            'user_id' => auth()->user()->id,
            'plan_id' => $this->plan->id,
            'status' => UserPlanStatus::Pending,
            'billing_cycle' => $this->billingCycle,
            'amount' => $this->price,
            'currency' => $this->plan->currency,
        ]);

        return redirect()->route(Checkout::getRouteName('app'), ['order_id' => $userPlan->id]);
    }

    public function calculatePrice(): void
    {
        if ($this->billingCycle == 'monthly') {
            $this->price = $this->plan->month_price;
        } else {
            $this->price = $this->plan->year_price;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        $this->plan = Plan::query()->with('planFeatures.feature')->where('key', 'premium')->first();

        if (! $this->plan) {
            return;
        }

        $this->calculatePrice();

        return view('livewire.components.upgrade-modal', [
            'plan' => $this->plan,
        ]);
    }
}
