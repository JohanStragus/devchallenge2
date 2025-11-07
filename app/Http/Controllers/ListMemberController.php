<?php

namespace App\Http\Controllers;

use App\Models\{ListModel, User};
use Illuminate\Http\Request;

class ListMemberController extends Controller
{
    // POST /lists/{list}/members
    // Invitar/añadir o actualizar rol (owner/editor) por email o user_id
    public function store(Request $request, ListModel $list)
    {
        $this->authorize('manageMembers', $list); // solo owner

        // email O user_id (uno de los dos es obligatorio)
        $data = $request->validate([
            'email'   => ['nullable', 'email', 'exists:users,email', 'required_without:user_id'],
            'user_id' => ['nullable', 'exists:users,id', 'required_without:email'],
            'role'    => ['required', 'in:owner,editor'],
        ], [], [
            'email'   => 'correo',
            'user_id' => 'usuario',
            'role'    => 'rol',
        ]);

        // Resolver usuario destino
        $target = isset($data['email'])
            ? User::where('email', $data['email'])->first()
            : User::find($data['user_id']);

        // Impedir auto-invitarse
        if ($target->id === $request->user()->id) {
            return response()->json(['message' => 'No puedes invitarte a ti mismo.'], 422);
        }

        // Añadir o actualizar rol en la pivot
        $list->members()->syncWithoutDetaching([
            $target->id => ['role' => $data['role']],
        ]);
        $list->members()->updateExistingPivot($target->id, ['role' => $data['role']]);

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

        if ($user->id === $list->id_user && $data['role'] !== 'owner') {
            return response()->json([
                'message' => 'No puedes degradar al propietario principal de la lista.',
            ], 422);
        }

        $list->members()->syncWithoutDetaching([$user->id => ['role' => $data['role']]]);
        $list->members()->updateExistingPivot($user->id, ['role' => $data['role']]);

        return response()->json(['ok' => true]);
    }

    // DELETE /lists/{list}/members/{user}
    // Quitar miembro de la lista
    public function destroy(ListModel $list, User $user)
    {
        $this->authorize('manageMembers', $list); // solo owner

        if ($user->id === $list->id_user) {
            return response()->json([
                'message' => 'No puedes eliminar al propietario principal de la lista.',
            ], 422);
        }

        $list->members()->detach($user->id);

        return response()->json(['ok' => true]);
    }
}
