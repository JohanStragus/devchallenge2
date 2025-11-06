<?php

namespace App\Policies;

use App\Models\{Category, ListModel, User};

class CategoryPolicy
{
    /**
     * Crear una categoría dentro de una lista (solo owner o editor).
     */
    public function createInList(User $user, ListModel $list): bool
    {
        if ($user->id === $list->id_user) {
            return true;
        }

        $pivot = $list->members()->where('id_user', $user->id)->first()?->pivot;
        return $pivot && $pivot->role === 'editor';
    }

    /**
     * Actualizar una categoría (solo owner o editor de la lista).
     */
    public function update(User $user, Category $category, ListModel $list): bool
    {
        if ($user->id === $list->id_user) {
            return true;
        }

        $pivot = $list->members()->where('id_user', $user->id)->first()?->pivot;
        return $pivot && $pivot->role === 'editor';
    }

    /**
     * Adjuntar categoría existente a una lista (owner o editor).
     */
    public function attachToList(User $user, ListModel $list): bool
    {
        return $this->createInList($user, $list);
    }

    /**
     * Quitar categoría de una lista (owner o editor).
     */
    public function detachFromList(User $user, ListModel $list): bool
    {
        return $this->createInList($user, $list);
    }
}
