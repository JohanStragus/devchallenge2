<?php

namespace App\Policies;

use App\Models\{User, ListModel, Category};

class CategoryPolicy
{
    /**
     * Owner o editor de la lista.
     */
    private function canEditList(User $user, ListModel $list): bool
    {
        if ($user->id === $list->id_user) return true;

        $role = $list->members()
            ->where('id_user', $user->id)
            ->value('role');

        return $role === 'editor';
    }

    /**
     * Crear una categoría dentro de una lista.
     * Usado por: $this->authorize('createInList', [Category::class, $list])
     */
    public function createInList(User $user, ListModel $list): bool
    {
        return $this->canEditList($user, $list);
    }

    /**
     * Actualizar una categoría ligada a una lista.
     * Usado por: $this->authorize('update', [$category, $list])
     */
    public function update(User $user, Category $category, ListModel $list): bool
    {
        return $this->canEditList($user, $list);
    }

    /**
     * Adjuntar categoría existente a una lista.
     * Usado por: $this->authorize('attachToList', [Category::class, $list])
     */
    public function attachToList(User $user, ListModel $list): bool
    {
        return $this->canEditList($user, $list);
    }

    /**
     * Quitar categoría de una lista.
     * Usado por: $this->authorize('detachFromList', [Category::class, $list])
     */
    public function detachFromList(User $user, ListModel $list): bool
    {
        return $this->canEditList($user, $list);
    }
}
