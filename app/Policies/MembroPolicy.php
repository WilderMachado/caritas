<?php

namespace caritas\Policies;

use caritas\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MembroPolicy
{
    use HandlesAuthorization;

    public function visualizar(User $user)
    {
        return $user->tipo == 'admin' || $user->tipo == 'comum';
    }

    public function salvar(User $user)
    {
        return $user->tipo == 'admin';
    }

    public function alterar(User $user)
    {
        return $user->tipo == 'admin';
    }

    public function excluir(User $user)
    {
        return $user->tipo == 'admin';
    }

    public function detalhar(User $user)
    {
        return $user->tipo == 'admin' || $user->tipo == 'comum';
    }

    public function acao(User $user)
    {
        return $this->alterar($user) || $this->excluir($user) || $this->detalhar($user);
    }
}
