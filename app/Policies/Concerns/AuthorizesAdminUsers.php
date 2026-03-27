<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait AuthorizesAdminUsers
{
    protected function isAdmin(User $user): bool
    {
        return strtoupper((string) ($user->utype ?? '')) === 'ADM';
    }
}
