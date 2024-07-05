<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Payouts') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Payouts you've received from your referrals.
        </p>
    </header>

    <div class="mt-6">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="py-3 pl-0 text-left">Date</th>
                    <th class="py-3 pl-0 text-left">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($payouts as $payout)
                    <tr>
                        <td class="py-3 pl-0 text-left">
                            {{ $payout->paid_at->toDateString() }}
                        </td>
                        <td class="py-3 pl-0 text-left">
                            {{ $payout->amount }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-gray-500">-</td>
                        <td class="text-gray-500">-</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
