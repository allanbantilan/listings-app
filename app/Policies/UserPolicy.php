<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function modidyrole(User $user)
    {
        return $user->isAdmin();
    }
}
