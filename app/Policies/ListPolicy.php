<?php

namespace App\Policies;

use App\Models\{ListModel, User};

class ListPolicy
{
    /**
     * Un usuario puede ver la lista si es dueño o miembro.
     */
    public function view(User $user, ListModel $list): bool
    {
        return $user->id === $list->id_user
            || $list->members->contains($user->id);
    }

    /**
     * Puede actualizar (editar nombre) si es dueño o editor.
     */
    public function update(User $user, ListModel $list): bool
    {
        if ($user->id === $list->id_user) {
            return true;
        }

        $pivot = $list->members()->where('id_user', $user->id)->first()?->pivot;
        return $pivot && $pivot->role === 'editor';
    }

    /**
     * Solo el owner puede eliminar la lista.
     */
    public function delete(User $user, ListModel $list): bool
    {
        return $user->id === $list->id_user;
    }

    /**
     * Crear listas (cualquiera autenticado).
     */
    public function create(User $user): bool
    {
        return $user !== null;
    }

    /**
     * Gestionar miembros (solo owner).
     */
    public function manageMembers(User $user, ListModel $list): bool
    {
        return $user->id === $list->id_user;
    }
}
