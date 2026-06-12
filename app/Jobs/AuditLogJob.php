<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\AuditService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AuditLogJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $action,
        public ?string $model = null,
        public ?int $modelId = null,
        public ?array $changes = null
    ) {}

    public function handle(AuditService $auditService): void
    {
        $auditService->log(
            $this->action,
            $this->model,
            $this->modelId,
            $this->changes
        );
    }
}
