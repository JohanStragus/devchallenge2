<?php

namespace App\Policies;

use App\Models\{ListModel, User};

class ListModelPolicy
{
    public function view(User $user, ListModel $list): bool
    {
        return $user->id === $list->id_user || $list->members->contains($user->id);
    }

    public function update(User $user, ListModel $list): bool
    {
        if ($user->id === $list->id_user) return true;
        $pivot = $list->members()->where('id_user',$user->id)->first()?->pivot;
        return $pivot && $pivot->role === 'editor';
    }

    public function delete(User $user, ListModel $list): bool
    {
        return $user->id === $list->id_user;
    }

    public function manageMembers(User $user, ListModel $list): bool
    {
        return $user->id === $list->id_user;
    }
}
