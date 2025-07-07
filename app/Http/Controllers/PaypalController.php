<?php

namespace App\Http\Controllers;

use App\Events\PaypalSuccessEvent;
use App\Filament\App\Pages\UserPlanHistory;
use App\Models\PaymentTransaction;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Facades\PayPal;

class PaypalController extends Controller
{
    public function success(Request $request)
    {
        $token = $request->query('token');      // PayPal gửi về ?token=xxx

        $provider = PayPal::setProvider();
        $provider->setApiCredentials(config('paypal'));
        $provider->setAccessToken($provider->getAccessToken());

        $capture = $provider->capturePaymentOrder($token);   // CAPTURE

        /**
         * array:6 [▼
         * "id" => "2XS58787138271737"
         * "status" => "COMPLETED"
         * "payment_source" => array:1 [▼
         * "paypal" => array:5 [▼
         * "email_address" => "sb-a7hkx43439361@personal.example.com"
         * "account_id" => "6TSBGJUSXWZDQ"
         * "account_status" => "VERIFIED"
         * "name" => array:2 [▶]
         * "address" => array:1 [▶]
         * ]
         * ]
         * "purchase_units" => array:1 [▼
         * 0 => array:3 [▼
         * "reference_id" => "TM-68465ccb8bfe6"
         * "shipping" => array:2 [▶]
         * "payments" => array:1 [▶]
         * ]
         * ]
         * "payer" => array:4 [▼
         * "name" => array:2 [▼
         * "given_name" => "John"
         * "surname" => "Doe"
         * ]
         * "email_address" => "sb-a7hkx43439361@personal.example.com"
         * "payer_id" => "6TSBGJUSXWZDQ"
         * "address" => array:1 [▼
         * "country_code" => "US"
         * ]
         * ]
         * "links" => array:1 [▼
         * 0 => array:3 [▼
         * "href" => "https://api.sandbox.paypal.com/v2/checkout/orders/2XS58787138271737"
         * "rel" => "self"
         * "method" => "GET"
         * ]
         * ]
         * ]
         */
        if (($capture['status'] ?? '') === 'COMPLETED') {
            $userPlanId = $capture['purchase_units'][0]['reference_id'] ?? 0;
            $userPlan = UserPlan::with('plan')->findOrFail($userPlanId);

            $code = sprintf('TM%05d', $userPlanId);

            $model = new PaymentTransaction;
            $model->transaction_number = microtime(true);
            $model->gateway = 'paypal';
            //            $model->transaction_date = $sePayWebhookData->transactionDate;
            $model->account_number = $capture['payer']['email_address'];
            //            $model->sub_account = $sePayWebhookData->subAccount;
            $model->code = $code;
            //            $model->content = $sePayWebhookData->content;
            //            $model->transfer_type = $sePayWebhookData->transferType;
            $model->reference_code = $capture['id'];
            $model->amount = $userPlan->amount;
            $model->accumulated = $userPlan->amount;
            $model->description = json_encode($capture);
            $model->save();

            // Notify user or perform other actions
            // You can use a notification system or event to notify the user
            event(new PaypalSuccessEvent($userPlan, $model));

            return redirect()->route(UserPlanHistory::getRouteName('app'));
        }

        abort('500', 'Payment capture failed or not completed.');
    }

    public function cancel(Request $request)
    {
        Log::error('Payment cancelled by user.', [
            'request' => $request->all(),
        ]);

        return abort(403, 'Payment cancelled by user.');
    }

    public function webhook(Request $request)
    {

        \Log::info(json_encode($request->all()));
        // Xử lý sự kiện webhook
    }
}
