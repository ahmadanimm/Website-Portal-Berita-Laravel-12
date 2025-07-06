<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\SubscriptionExpiring;
use Carbon\Carbon;

class NotifySubscriptionExpiring extends Command
{
    protected $signature = 'notify:subscription-expiring';

    protected $description = 'Kirim notifikasi ke user yang langganannya akan habis dalam 3 hari';

    public function handle()
    {
        $targetDate = Carbon::now()->addDays(3)->startOfDay();

        $users = User::where('is_subscribed', true)
            ->whereDate('subscribed_until', $targetDate)
            ->get();

        foreach ($users as $user) {
            $user->notify(new SubscriptionExpiring());
            $this->info("Notifikasi terkirim ke user: {$user->email}");
        }

        return 0;
    }
}
