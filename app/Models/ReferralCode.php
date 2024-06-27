<?php

namespace App\Models;

use Laravel\Cashier\Cashier;
use App\Observers\ReferralCodeObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(ReferralCodeObserver::class)]
class ReferralCode extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Cashier::$subscriptionModel)
            ->withPivot('multiplier');
    }
}
