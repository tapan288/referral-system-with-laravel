<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReferralPayoutExport implements FromCollection, WithMapping
{
    public function __construct(protected Builder $payouts)
    {
        //
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->payouts->get();
    }

    public function map($payout): array
    {
        return [
            $payout->paypal_email,
            $payout->amount,
            'USD',
        ];
    }
}
