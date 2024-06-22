<?php

namespace App\Http\Controllers;

use App\Models\ReferralCode;
use Illuminate\Http\Request;

class ReferralsController extends Controller
{
    public function index()
    {
        return view('referrals.index');
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
