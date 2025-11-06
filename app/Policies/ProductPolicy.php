<?php

namespace App\Policies;

use App\Models\{Product, ListModel, User};

class ProductPolicy
{
    /**
     * Crear un producto dentro de una lista (solo owner o editor).
     */
    public function create(User $user, ListModel $list): bool
    {
        if ($user->id === $list->id_user) {
            return true;
        }

        $pivot = $list->members()->where('id_user', $user->id)->first()?->pivot;
        return $pivot && $pivot->role === 'editor';
    }

    /**
     * Actualizar producto (nombre o categoría) → owner/editor.
     */
    public function update(User $user, Product $product, ListModel $list): bool
    {
        return $this->create($user, $list);
    }

    /**
     * Alternar estado de completado (toggle) → owner/editor.
     */
    public function toggle(User $user, Product $product, ListModel $list): bool
    {
        return $this->create($user, $list);
    }

    /**
     * Eliminar producto (detach o delete) → solo owner.
     */
    public function delete(User $user, Product $product, ListModel $list): bool
    {
        return $user->id === $list->id_user;
    }

    /**
     * Adjuntar producto existente a lista (owner/editor).
     */
    public function attach(User $user, ListModel $list): bool
    {
        return $this->create($user, $list);
    }
}
