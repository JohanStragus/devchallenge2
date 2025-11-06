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
     * Eliminar comentario → autor o dueño de la lista.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $this->update($user, $comment);
    }
}
