<?php

namespace App\Policies;

use App\Models\{Comment, ListModel, User};

class CommentPolicy
{
    /**
     * Ver comentarios de una lista → miembros o dueño.
     */
    public function view(User $user, ListModel $list): bool
    {
        return $user->id === $list->id_user
            || $list->members->contains($user->id);
    }

    /**
     * Crear comentarios → miembros o dueño.
     */
    public function create(User $user, ListModel $list): bool
    {
        return $this->view($user, $list);
    }

    /**
     * Editar comentario → autor o dueño de la lista.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->id_user
            || $user->id === $comment->list->id_user;
    }

    /**
     * Eliminar comentario → autor, owner pivot o dueño real.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // autor
        if ($user->id === $comment->id_user) return true;

        $list = $comment->list;

        // dueño real
        if ($user->id === $list->id_user) return true;

        // owner pivot, pero no puede borrar los del propietario real
        $role = $list->members()->where('id_user', $user->id)->value('role');
        return $role === 'owner' && $comment->id_user !== $list->id_user;
    }
}
