<?php

namespace App\Traits;

use App\Models\ReferralCode;
use App\Models\ReferralPayment;

trait HasReferrals
{
    public function referralCode()
    {
        return $this->hasOne(ReferralCode::class);
    }

    public function referralsEnabled()
    {
        return $this->hasReferralCode() && !is_null($this->paypal_email);
    }

    public function hasReferralCode()
    {
        return $this->referralCode()->exists();
    }

    public function referralPayments()
    {
        return $this->hasMany(ReferralPayment::class);
    }

    public function referralLink()
    {
        return route('referrals.show', $this->referralCode);
    }
}
