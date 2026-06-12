<?php

namespace App\Listeners;

use App\Events\SuspiciousActivity;
use App\Jobs\NotifyAdministrators;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyOnSuspiciousActivity implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(SuspiciousActivity $event): void
    {
        $message = "Suspicious activity detected";
        if ($event->user) {
            $message .= " for user {$event->user->email}";
        }
        $message .= ": {$event->reason}";

        NotifyAdministrators::dispatch(
            'Suspicious Activity Alert',
            $message,
            $event->data
        );
    }
}
