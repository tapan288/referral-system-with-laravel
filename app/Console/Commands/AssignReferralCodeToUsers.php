<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AssignReferralCodeToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-referral-code-to-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign referral codes to users.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::whereDoesntHave('referralCode')->chunk(100, function ($users) {
            $users->each(function ($user) {
                $user->referralCode()->create();
            });
        });
    }
}
