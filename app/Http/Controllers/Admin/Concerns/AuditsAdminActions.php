<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\AdminAuditLog;

trait AuditsAdminActions
{
    protected function auditAdminAction(string $action, ?string $targetType = null, mixed $targetId = null, array $payload = []): void
    {
        AdminAuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'route' => request()->route()?->getName(),
            'ip_address' => request()->ip(),
            'payload' => $payload,
        ]);
    }
}