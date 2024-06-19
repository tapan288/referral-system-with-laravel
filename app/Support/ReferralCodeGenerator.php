<?php

namespace App\Support;

use Illuminate\Support\Str;
use App\Models\ReferralCode;

class ReferralCodeGenerator
{
    public static function generate()
    {
        $code = self::generateCode();

        while (ReferralCode::where('code', $code)->exists()) {
            $code = self::generateCode();
        }

        return $code;
    }

    public static function generateCode()
    {
        return Str::random(8);
    }
}
