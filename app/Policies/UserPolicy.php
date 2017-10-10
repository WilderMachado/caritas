<?php

namespace caritas\Policies;

use caritas\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function visualizar(User $user)
    {
        return $user->tipo == 'admin';
    }

    public function salvar(User $user)
    {
        return $user->tipo == 'admin';
    }
}
