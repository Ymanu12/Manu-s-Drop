<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Policies\Concerns\AuthorizesAdminUsers;

class ProductPolicy
{
    use AuthorizesAdminUsers;

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(User $user, Product $product): bool
    {
        return $this->isAdmin($user);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, Product $product): bool
    {
        return $this->isAdmin($user);
    }

    public function delete(User $user, Product $product): bool
    {
        return $this->isAdmin($user);
    }
}
