<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Log activity
     */
    public function log(string $action, ?string $model = null, ?int $modelId = null, ?array $changes = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    /**
     * Get user audit logs
     */
    public function getUserLogs(User $user, int $limit = 50)
    {
        return $user->auditLogs()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get model audit logs
     */
    public function getModelLogs(string $model, ?int $modelId = null, int $limit = 50)
    {
        $query = AuditLog::byModel($model);

        if ($modelId) {
            $query->where('model_id', $modelId);
        }

        return $query->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get action audit logs
     */
    public function getActionLogs(string $action, int $limit = 50)
    {
        return AuditLog::byAction($action)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent audit logs
     */
    public function getRecent(int $days = 7, int $limit = 100)
    {
        return AuditLog::recent($days)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get suspicious activities
     */
    public function getSuspiciousActivities()
    {
        return AuditLog::whereIn('action', [
            'failed_login',
            'suspicious_activity',
            'permission_revoked',
            'user_deleted',
        ])
            ->latest()
            ->get();
    }
}
