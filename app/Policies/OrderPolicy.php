<?php

namespace App\Policies;

use App\Models\Orders;
use App\Models\User;
use App\Policies\Concerns\AuthorizesAdminUsers;

class OrderPolicy
{
    use AuthorizesAdminUsers;

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(User $user, Orders $order): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, Orders $order): bool
    {
        return $this->isAdmin($user);
    }
}
