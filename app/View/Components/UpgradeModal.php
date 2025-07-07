<?php

namespace App\View\Components;

use App\Models\Plan;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UpgradeModal extends Component
{
    public ?Plan $plan = null;

    public string $billingCycle = 'monthly';

    public float $billingPrice = 0;

    public function __construct()
    {
        $this->plan = Plan::query()->where('name->en', 'Premium')->first();
    }

    public function updateBillingCycle($billingCycle): void
    {
        $this->billingCycle = $billingCycle;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if ($this->billingCycle == 'monthly') {
            $this->billingPrice = $this->plan->price;
        } else {
            $this->billingPrice = $this->plan->price * 12 * 0.8;
        }

        return view('components.upgrade-modal', [
            'plan' => $this->plan,
        ]);
    }
}
