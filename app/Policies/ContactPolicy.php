<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use App\Policies\Concerns\AuthorizesAdminUsers;

class ContactPolicy
{
    use AuthorizesAdminUsers;

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(User $user, Contact $contact): bool
    {
        return $this->isAdmin($user);
    }

    public function delete(User $user, Contact $contact): bool
    {
        return $this->isAdmin($user);
    }
}
