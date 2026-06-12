<?php

namespace App\Listeners;

use App\Events\UserLogin;
use App\Services\AuditService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserLogin implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private AuditService $auditService
    ) {}

    public function handle(UserLogin $event): void
    {
        $this->auditService->log(
            'login',
            'User',
            $event->user->id,
            ['user_id' => $event->user->id]
        );
    }
}
