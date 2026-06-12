<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendMfaCode implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user,
        public string $method,
        public string $code
    ) {}

    public function handle(): void
    {
        // TODO: Send MFA code via email, SMS, etc.
        Log::info("MFA Code sent to {$this->user->email} via {$this->method}: {$this->code}");
    }
}
