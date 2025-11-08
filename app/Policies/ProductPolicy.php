<?php

namespace App\Policies;

use App\Models\{User, ListModel};

class ProductPolicy
{
    private function canEditList(User $user, ListModel $list): bool
    {
        if ($user->id === $list->id_user) return true;

        $role = $list->members()->where('id_user', $user->id)->value('role');
        return in_array($role, ['owner', 'editor']);
    }

    public function create(User $user, ListModel $list): bool
    {
        return $this->canEditList($user, $list);
    }

    public function update(User $user, ListModel $list): bool
    {
        return $this->canEditList($user, $list);
    }

    public function toggle(User $user, ListModel $list): bool
    {
        return $this->canEditList($user, $list);
    }

    public function delete(User $user, ListModel $list): bool
    {
        return $this->canEditList($user, $list);
    }

    public function attach(User $user, ListModel $list): bool
    {
        return $this->canEditList($user, $list);
    }
}
