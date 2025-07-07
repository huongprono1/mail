<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PaymentTransaction
 *
 * This class represents payment transactions in the application.
 *
 * @property int $transaction_number The unique identifier for the transaction.
 * @property string $gateway The payment gateway used for the transaction.
 * @property \Carbon\Carbon $transaction_date The date and time when the transaction occurred.
 * @property string $account_number The account number associated with the transaction.
 * @property string $code The transaction code.
 * @property string $content The content or purpose of the transaction.
 * @property string $transfer_type The type of transfer (e.g., credit, debit).
 * @property float $amount The amount involved in the transaction.
 * @property float $accumulated The accumulated amount for the transaction.
 * @property string $sub_account The sub-account associated with the transaction.
 * @property string $reference_code The reference code for the transaction.
 */
class PaymentTransaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'transaction_number',
        'gateway',
        'transaction_date',
        'account_number',
        'code',
        'content',
        'transfer_type',
        'amount',
        'accumulated',
        'sub_account',
        'reference_code',
        'description',
    ];

    public function userPlan(): HasOne
    {
        return $this->hasOne(UserPlan::class, 'payment_transaction_id');
    }
}
