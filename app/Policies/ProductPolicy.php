<?php

namespace App\Policies;

use App\Models\{User, ListModel};

class ProductPolicy
{
    private function canEditList(User $user, ListModel $list): bool
    {
        if ($user->id === $list->id_user) return true;

        $role = $list->members()->where('id_user', $user->id)->value('role');
        return $role === 'editor';
    }

    // $this->authorize('create', [Product::class, $list])
    public function create(User $user, ListModel $list): bool
    {
        return $this->canEditList($user, $list);
    }

    // $this->authorize('update', [Product::class, $list])
    public function update(User $user, ListModel $list): bool
    {
        return $this->canEditList($user, $list);
    }

    // $this->authorize('toggle', [Product::class, $list])
    public function toggle(User $user, ListModel $list): bool
    {
        return $this->canEditList($user, $list);
    }

    // $this->authorize('delete', [Product::class, $list])
    public function delete(User $user, ListModel $list): bool
    {
        return $user->id === $list->id_user; // solo owner borra
    }

    // $this->authorize('attach', [Product::class, $list])
    public function attach(User $user, ListModel $list): bool
    {
        return $this->canEditList($user, $list);
    }
}
