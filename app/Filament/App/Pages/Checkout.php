<?php

namespace App\Filament\App\Pages;

use App\Enums\UserPlanStatus;
use App\Models\UserPlan;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Srmklive\PayPal\Facades\PayPal;

class Checkout extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.checkout';

    //    #[Url(as: 'order_id')]
    public int $id = 0;

    public ?UserPlanStatus $status;

    public UserPlan $userPlan;

    public function getTitle(): string|Htmlable
    {
        return __('Checkout');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function mount()
    {
        $this->id = request()->get('order_id');
        if (!$this->id) {
            abort(404);
        }

        $this->userPlan = UserPlan::with('plan')->findOrFail($this->id);

    }

    public function payWithPaypal()
    {
        $provider = PayPal::setProvider();
        $provider->setApiCredentials(config('paypal'));

        $provider->setAccessToken($provider->getAccessToken());

        $order = $provider->createOrder([
            'intent' => 'CAPTURE',           // hoặc 'AUTHORIZE'
            'application_context' => [
                'return_url' => route('callback.paypal.success'),
                'cancel_url' => route('callback.paypal.cancel'),
                'brand_name' => config('app.name'),
                'user_action' => 'PAY_NOW',
            ],
            'purchase_units' => [[
                'invoice_id' => $this->userPlan->id,
                'description' => __('Payment for :plan (:billing_cycle)', [
                    'plan' => $this->userPlan->plan->name,
                    'billing_cycle' => $this->userPlan->billing_cycle,
                ]),
                'reference_id' => $this->userPlan->id,
                'amount' => [
                    'currency_code' => $this->userPlan->currency,
                    'value' => $this->userPlan->amount + 0.42, // Phí dịch vụ của PayPal
                ],
            ]],
        ]);

        // Lấy link 'approve' để redirect
        if (isset($order['id']) && $order['status'] === 'CREATED') {
            $approve = collect($order['links'])
                ->firstWhere('rel', 'approve')['href'] ?? null;
            if ($approve) {
                redirect()->away($approve);
            }
        }

        Notification::make('error')->danger()
            ->title(__('Unable to create PayPal order'))
            ->body($order['error']['details'][0]['issue'] ?? __('An error occurred while creating the PayPal order.'))
            ->send();
    }

    public function checkOrderStatus(): void
    {
        $this->status = $this->userPlan->status;
    }
}
