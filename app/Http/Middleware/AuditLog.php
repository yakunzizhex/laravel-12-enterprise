<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AuditService;

class AuditLog
{
    /**
     * Constructor
     */
    public function __construct(
        private AuditService $auditService
    ) {}

    /**
     * Handle incoming request
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log certain actions only
        if ($this->shouldLog($request)) {
            $this->auditService->log(
                action: $this->getActionName($request),
                changes: $this->getChanges($request)
            );
        }

        return $response;
    }

    /**
     * Check if request should be logged
     */
    private function shouldLog(Request $request): bool
    {
        return in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH'])
            && !in_array($request->path(), ['/login', '/logout']);
    }

    /**
     * Get action name from route
     */
    private function getActionName(Request $request): string
    {
        $route = $request->route();
        $action = $route?->getActionMethod() ?? 'unknown';
        $method = $request->method();

        return strtolower("{$method}_{$action}");
    }

    /**
     * Get request changes
     */
    private function getChanges(Request $request): array
    {
        return $request->all();
    }
}
