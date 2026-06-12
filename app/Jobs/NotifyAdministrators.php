<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class NotifyAdministrators implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $subject,
        public string $message,
        public ?array $data = null
    ) {}

    public function handle(): void
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->where('slug', 'admin');
        })->pluck('email');

        // TODO: Send notifications
        // Mail::send('emails.notification', [
        //     'subject' => $this->subject,
        //     'message' => $this->message,
        //     'data' => $this->data,
        // ], function ($message) use ($admins) {
        //     $message->to($admins);
        // });
    }
}
