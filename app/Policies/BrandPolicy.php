<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;
use App\Policies\Concerns\AuthorizesAdminUsers;

class BrandPolicy
{
    use AuthorizesAdminUsers;

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(User $user, Brand $brand): bool
    {
        return $this->isAdmin($user);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, Brand $brand): bool
    {
        return $this->isAdmin($user);
    }

    public function delete(User $user, Brand $brand): bool
    {
        return $this->isAdmin($user);
    }
}
