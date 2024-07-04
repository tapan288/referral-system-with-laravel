<?php

namespace App\Listeners;

use Illuminate\Support\Arr;
use App\Models\ReferralCode;
use App\Models\Subscription;
use App\Models\ReferralPayment;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Cashier\Events\WebhookReceived;

class StripeEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WebhookReceived $event): void
    {
        match ($event->payload['type']) {
            'customer.subscription.updated' =>
            $this->handleSubscriptionUpdated($event->payload),
            'invoice.payment_succeeded' =>
            $this->handleInvoicePaymentSucceeded($event->payload),
            default => null,
        };
    }

    protected function handleSubscriptionUpdated($payload)
    {
        $referralCode = ReferralCode::query()
            ->where('code', Arr::get($payload, 'data.object.metadata.referral_code'))
            ->first();

        retry(5, function () use ($payload, $referralCode) {
            $subscription = $this->getSubscriptionByStripeId(
                Arr::get($payload, 'data.object.id')
            );

            $referralCode->subscriptions()->syncWithoutDetaching([
                $subscription->id => [
                    'multiplier' => config('referral.multiplier')
                ]
            ]);
        }, 500);
    }

    protected function handleInvoicePaymentSucceeded($payload)
    {
        retry(5, function () use ($payload) {
            $subscription = $this->getSubscriptionByStripeId(
                Arr::get($payload, 'data.object.subscription')
            );

            $referralCode = $subscription->referralCodes->firstOrFail();

            ReferralPayment::firstOrCreate([
                'stripe_id' => Arr::get($payload, 'data.object.id'),
            ], [
                'user_id' => $referralCode->user->id,
                'referrer_user_id' => $subscription->user->id,
                'payment_total' => $total = Arr::get($payload, 'data.object.total'),
                'amount' => $referralCode->pivot->multiplier * ceil($total),
                'available_at' => now()->endOfDay()->addMonth(),
            ]);
        }, 500);
    }

    protected function getSubscriptionByStripeId($stripeId)
    {
        return Subscription::query()
            ->where('stripe_id', $stripeId)
            ->firstOrFail();
    }
}
