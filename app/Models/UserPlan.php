<?php

namespace App\Models;

use App\Enums\UserPlanStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id The ID of the user associated with the plan.
 * @property User $user User info
 * @property int $plan_id The ID of the plan.
 * @property float $amount Amount.
 * @property string $billing_cycle Billing cycle.
 * @property string $currency Currency.
 * @property Plan $plan Plan info
 * @property int $payment_transaction_id The transaction ID for the payment.
 * @property PaymentTransaction $paymentTransaction payment transaction info
 * @property \Carbon\Carbon $started_at The date and time when the plan started.
 * @property \Carbon\Carbon $expired_at The date and time when the plan expires.
 * @property UserPlanStatus $status The current status of the plan (e.g., active, inactive).
 */
class UserPlan extends Model
{
    protected $table = 'user_plans';

    protected $fillable = [
        'user_id',
        'plan_id',
        'payment_transaction_id',
        'started_at',
        'expired_at',
        'status',
        'amount',
        'billing_cycle',
        'currency',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    protected function casts(): array
    {
        return [
            'status' => UserPlanStatus::class,
            'expired_at' => 'datetime',
            'started_at' => 'datetime',
        ];
    }

    public function paymentTransaction(): BelongsTo
    {
        return $this->belongsTo(PaymentTransaction::class);
    }
}
