<?php

namespace App\Policies;

use App\Models\Usuario;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsuarioPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    use HandlesAuthorization;

    // Método para determinar si el usuario puede realizar acciones como administrador
    public function isAdmin(Usuario $user)
    {
        return $user->rol == 1; // Suponiendo que 1 es el rol de administrador en tu tabla
    }

    // Método para determinar si el usuario puede realizar acciones como usuario común
    public function isUsuario(Usuario $user)
    {
        return $user->rol == 2; // Suponiendo que 2 es el rol de usuario común en tu tabla
    }
}
