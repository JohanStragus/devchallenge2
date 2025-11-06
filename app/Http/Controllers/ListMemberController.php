<?php

namespace App\Http\Controllers;

use App\Models\{ListModel, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListMemberController extends Controller
{
    // POST /lists/{list}/members
    // Invitar/añadir o actualizar rol (owner/editor)
    public function store(Request $request, ListModel $list)
    {
        $this->authorize('manageMembers', $list); // solo el owner real

        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id', 'different:auth_user'],
            'role'    => ['required', 'in:owner,editor'],
        ], [], [
            'user_id' => 'usuario',
            'role'    => 'rol',
        ]);

        // Evita auto-asignarse vía validación preparada:
        $request->merge(['auth_user' => Auth::id()]);

        // No duplicar: añade o actualiza sin soltar vínculos previos
        $list->members()->syncWithoutDetaching([
            $data['user_id'] => ['role' => $data['role']],
        ]);

        return response()->json(['ok' => true]);
    }

    // PATCH /lists/{list}/members/{user}
    // Cambiar rol del miembro
    public function update(Request $request, ListModel $list, User $user)
    {
        $this->authorize('manageMembers', $list); // solo owner

        $data = $request->validate([
            'role' => ['required', 'in:owner,editor'],
        ]);

        // (Opcional) impedir que el owner se degrade si es el único owner de la lista
        if ($user->id === $list->id_user && $data['role'] !== 'owner') {
            return response()->json([
                'message' => 'No puedes degradar al propietario principal de la lista.',
            ], 422);
        }

        // Si el user no estaba en la pivot, lo adjuntamos con el rol
        $list->members()->syncWithoutDetaching([$user->id => ['role' => $data['role']]]);
        // Si ya estaba, actualizamos su rol
        $list->members()->updateExistingPivot($user->id, ['role' => $data['role']]);

        return response()->json(['ok' => true]);
    }

    // DELETE /lists/{list}/members/{user}
    // Quitar miembro de la lista
    public function destroy(ListModel $list, User $user)
    {
        $this->authorize('manageMembers', $list); // solo owner

        // (Opcional) impedir que quiten al owner principal
        if ($user->id === $list->id_user) {
            return response()->json([
                'message' => 'No puedes eliminar al propietario principal de la lista.',
            ], 422);
        }

        $list->members()->detach($user->id);

        return response()->json(['ok' => true]);
    }
}
