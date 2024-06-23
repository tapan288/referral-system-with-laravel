<?php

namespace App\Models;

use Akaunting\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Money::USD($value)
        );
    }
}
