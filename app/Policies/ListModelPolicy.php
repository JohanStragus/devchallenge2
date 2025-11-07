<?php

namespace App\Policies;

use App\Models\{ListModel, User};

class ListModelPolicy
{
    public function view(User $user, ListModel $list): bool
    {
        // Owner o miembro (editor u owner compartido)
        return $user->id === $list->id_user || $list->members->contains($user->id);
    }

    public function update(User $user, ListModel $list): bool
    {
        // Solo el propietario puede renombrar
        return $user->id === $list->id_user;
    }

    public function delete(User $user, ListModel $list): bool
    {
        // Solo el propietario puede eliminar
        return $user->id === $list->id_user;
    }

    public function manageMembers(User $user, ListModel $list): bool
    {
        // Solo el propietario puede gestionar miembros
        return $user->id === $list->id_user;
    }
}
