<?php

namespace App\Policies;

use App\Models\{ListModel, User};

class ListModelPolicy
{
    private function pivotRole(User $user, ListModel $list): ?string
    {
        return $list->members()->where('id_user', $user->id)->value('role');
    }

    public function view(User $user, ListModel $list): bool
    {
        return $user->id === $list->id_user
            || $this->pivotRole($user, $list) !== null; // owner/editor/viewer
    }

    public function update(User $user, ListModel $list): bool
    {
        // Solo propietario real puede renombrar
        return $user->id === $list->id_user;
    }

    public function delete(User $user, ListModel $list): bool
    {
        // Solo propietario real puede eliminar la lista
        return $user->id === $list->id_user;
    }

    public function manageMembers(User $user, ListModel $list): bool
    {
        // Propietario real o owner (pivot) pueden gestionar miembros
        if ($user->id === $list->id_user) return true;
        return $this->pivotRole($user, $list) === 'owner';
    }
}
