<x-filament-panels::page>

    <x-filament::section>
        <div class="flex items-center justify-between flex-wrap">
            <div>
                {{__('Plan')}}<br/>
                {{$userPlan->plan->name}}
            </div>
            <div>
                {{__('Billing Cycle')}}<br/>
                {{ $userPlan->billing_cycle == 'monthly' ? __('Monthly') : __('Yearly') }}
            </div>
            <div>
                {{__('Price')}}<br/>
                {{ \Illuminate\Support\Number::currency($userPlan->amount, $userPlan->plan->currency) }}
            </div>
        </div>

        @if($userPlan->status == \App\Enums\UserPlanStatus::Pending)
            @php
                $paymentContent = sprintf('TM%05d', $userPlan->id);
            @endphp
            <div class="w-full text-center mt-6">
                <div class="mb-6">
                    <img
                        src="https://img.vietqr.io/image/{{setting("site.payment_bank_name")}}-{{setting("site.payment_bank_number")}}-compact.png?amount={{ $userPlan->amount }}&addInfo={{ $paymentContent }}"
                        class="mx-auto rounded shadow-sm w-48" alt="QR"/>
                </div>
                <div class="mb-6">
                    <p class="font-bold text-warning text-uppercase mb-5">{{ __('Bank transfer content') }}</p>
                    <span
                        class="rounded-lg text-2xl py-3 px-6 font-semibold border-2 border-dashed border-amber-500 shadow text-amber-500 dark:text-amber-400">
                         {{ $paymentContent }}
                    </span>
                </div>
                <div class="text-skin-muted">
                    <svg class="m-auto size-6" viewBox="0 0 135 140" xmlns="http://www.w3.org/2000/svg"
                         fill="currentColor">
                        <rect y="10" width="15" height="120" rx="6">
                            <animate attributeName="height" begin="0.5s" dur="1s"
                                     values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear"
                                     repeatCount="indefinite"></animate>
                            <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10"
                                     calcMode="linear" repeatCount="indefinite"></animate>
                        </rect>
                        <rect x="30" y="10" width="15" height="120" rx="6">
                            <animate attributeName="height" begin="0.25s" dur="1s"
                                     values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear"
                                     repeatCount="indefinite"></animate>
                            <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10"
                                     calcMode="linear" repeatCount="indefinite"></animate>
                        </rect>
                        <rect x="60" width="15" height="140" rx="6">
                            <animate attributeName="height" begin="0s" dur="1s"
                                     values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear"
                                     repeatCount="indefinite"></animate>
                            <animate attributeName="y" begin="0s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10"
                                     calcMode="linear" repeatCount="indefinite"></animate>
                        </rect>
                        <rect x="90" y="10" width="15" height="120" rx="6">
                            <animate attributeName="height" begin="0.25s" dur="1s"
                                     values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear"
                                     repeatCount="indefinite"></animate>
                            <animate attributeName="y" begin="0.25s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10"
                                     calcMode="linear" repeatCount="indefinite"></animate>
                        </rect>
                        <rect x="120" y="10" width="15" height="120" rx="6">
                            <animate attributeName="height" begin="0.5s" dur="1s"
                                     values="120;110;100;90;80;70;60;50;40;140;120" calcMode="linear"
                                     repeatCount="indefinite"></animate>
                            <animate attributeName="y" begin="0.5s" dur="1s" values="10;15;20;25;30;35;40;45;50;0;10"
                                     calcMode="linear" repeatCount="indefinite"></animate>
                        </rect>
                    </svg>
                    <p class="text-sm">{{ __('waiting for payment') }}</p>
                </div>
            </div>
            <!-- Poll every 5 seconds to check the order status -->
            <div wire:poll.5s="checkOrderStatus"></div>


            <!--Payment Instructions-->
            @if(app()->currentLocale() == 'en')
                <div class="fi-section-footer border-t border-gray-200 dark:border-white/10 mt-6 pt-6">
                    <div class="text-center">
                        <x-filament::button color="info" wire:click.prevent="payWithPaypal" icon="fab-paypal" size="lg">
                            Pay with Paypal
                        </x-filament::button>
                        <p class="text-gray-400 text-sm mt-2">(Include $0.42 Paypal fee)</p>
                    </div>
                </div>
            @endif
        @else
            <x-filament::section class="mt-6">
                <div class="flex flex-col gap-4">
                    <div class="text-center">
                        <x-heroicon-s-check-circle class="w-24 h-24 text-green-600 mx-auto"/>
                        <div class="font-semibold text-green-400">
                            {{ __('Your order has been placed successfully.') }}
                        </div>
                        <div class="text-gray-400">
                            {{ __('You can find your order details in your account.') }}
                        </div>
                    </div>
                </div>
            </x-filament::section>
        @endif

    </x-filament::section>
</x-filament-panels::page>
