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
}
