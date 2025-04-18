<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\ReferralCode;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $referralCode = ReferralCode::query()
            ->where('code', $request->cookie('referral_code'))
            ->first();

        $plans = Plan::all();

        return view('dashboard', compact('referralCode', 'plans'));
    }
}
