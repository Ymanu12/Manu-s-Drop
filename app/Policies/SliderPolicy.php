<?php

namespace App\Policies;

use App\Models\Slider;
use App\Models\User;
use App\Policies\Concerns\AuthorizesAdminUsers;

class SliderPolicy
{
    use AuthorizesAdminUsers;

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(User $user, Slider $slider): bool
    {
        return $this->isAdmin($user);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, Slider $slider): bool
    {
        return $this->isAdmin($user);
    }

    public function delete(User $user, Slider $slider): bool
    {
        return $this->isAdmin($user);
    }
}
