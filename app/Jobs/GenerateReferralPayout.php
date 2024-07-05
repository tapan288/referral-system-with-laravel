<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Models\ReferralPayment;
use App\Notifications\ReferralPayout;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateReferralPayout implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payouts = ReferralPayment::query()
            ->where('available_at', '<=', now()->startOfDay())
            ->whereNull('paid_at');

        if ($payouts->count() === 0) {
            return;
        }

        $payouts->update([
            'paid_at' => now()
        ]);

        $records = $payouts
            ->selectRaw('SUM(amount) as amount,users.paypal_email')
            ->leftJoin('users', 'users.id', 'referral_payments.user_id')
            ->groupBy('user_id');

        User::where('email', 'admin@admin.com')
            ->first()
            ->notify(new ReferralPayout($records));
    }
}
