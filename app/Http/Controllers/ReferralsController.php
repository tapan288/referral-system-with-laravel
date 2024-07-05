<?php

namespace App\Http\Controllers;

use App\Models\ReferralCode;
use Illuminate\Http\Request;

class ReferralsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('referrals.index', [
            'referralCode' => $user->referralCode,
            'subscriptions' => $user
                ->referralCode
                ->subscriptions()
                ->notCanceled()
                ->get(),
            'payouts' => $user
                ->referralPayments()
                ->whereNotNull('paid_at')
                ->select('paid_at')
                ->selectRaw('SUM(amount) as amount')
                ->groupBy('paid_at')
                ->get()
        ]);
    }

    public function show(ReferralCode $referralCode)
    {
        $referralCode->increment('visits');

        return view('referrals.show', compact('referralCode'));
    }

    public function store(Request $request, ReferralCode $referralCode)
    {
        $referralCode->increment('clicks');

        cookie()->queue(
            cookie('referral_code', $referralCode->code, 60 * 24 * 30)
        );

        return redirect()->route('register');
    }
}
