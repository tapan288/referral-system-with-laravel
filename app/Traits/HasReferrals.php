<?php

namespace App\Traits;

use App\Models\ReferralCode;

trait HasReferrals
{
    public function referralCode()
    {
        return $this->hasOne(ReferralCode::class);
    }
}
