<?php

namespace App\Http\Controllers;

use App\Events\SePayWebhookEvent;
use App\Http\Datas\SePayWebhookData;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SePayController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function webhook(Request $request)
    {
        $token = $this->bearerToken($request);

        throw_if(
            config('sepay.webhook_token') && $token !== config('sepay.webhook_token'),
            ValidationException::withMessages(['message' => ['Invalid Token']])
        );

        $sePayWebhookData = new SePayWebhookData(
            $request->integer('id'),
            $request->string('gateway')->value(),
            $request->string('transactionDate')->value(),
            $request->string('accountNumber')->value(),
            $request->string('subAccount')->value(),
            $request->string('code')->value(),
            $request->string('content')->value(),
            $request->string('transferType')->value(),
            $request->string('description')->value(),
            $request->integer('transferAmount'),
            $request->string('referenceCode')->value(),
            $request->integer('accumulated')
        );

        throw_if(
            PaymentTransaction::query()->where('transaction_number', $sePayWebhookData->id)->where('gateway', $sePayWebhookData->gateway)->exists(),
            ValidationException::withMessages(['message' => ['transaction exists']])
        );

        $model = new PaymentTransaction;
        $model->transaction_number = $sePayWebhookData->id;
        $model->gateway = $sePayWebhookData->gateway;
        $model->transaction_date = $sePayWebhookData->transactionDate;
        $model->account_number = $sePayWebhookData->accountNumber;
        $model->sub_account = $sePayWebhookData->subAccount;
        $model->code = $sePayWebhookData->code;
        $model->content = $sePayWebhookData->content;
        $model->transfer_type = $sePayWebhookData->transferType;
        $model->description = $sePayWebhookData->description;
        $model->amount = $sePayWebhookData->transferAmount;
        $model->reference_code = $sePayWebhookData->referenceCode;
        $model->save();

        // Lấy ra user id hoặc order id ví dụ: SE_123456, SE_abcd-efgh
        $pattern = '/\b'.config('sepay.pattern').'([a-zA-Z0-9-_])+/';
        preg_match($pattern, $sePayWebhookData->content, $matches);

        if (isset($matches[0])) {
            // Lấy bỏ phần pattern chỉ còn lại id ex: 123456
            $info = Str::of($matches[0])->replaceFirst(config('sepay.pattern'), '')->value();
            event(new SePayWebhookEvent($info, $sePayWebhookData, $model));
        }

        return response()->noContent();
    }

    /**
     * Get the bearer token from the request headers.
     *
     * @return string|null
     */
    private function bearerToken(Request $request)
    {
        $header = $request->header('Authorization', '');

        $position = strrpos($header, 'Apikey ');

        if ($position !== false) {
            $header = substr($header, $position + 7);

            return str_contains($header, ',') ? (strstr($header, ',', true) ?: null) : $header;
        }

        return null;
    }
}
