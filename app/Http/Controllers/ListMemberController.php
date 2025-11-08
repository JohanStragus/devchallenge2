<?php

namespace App\Http\Controllers;

use App\Models\{ListModel, User};
use Illuminate\Http\Request;

class ListMemberController extends Controller
{
    // POST /lists/{list}/members  (invitar por email o user_id)
    public function store(Request $request, ListModel $list)
    {
        $this->authorize('manageMembers', $list); // propietario real o owner (pivot)

        $data = $request->validate([
            'email'   => ['nullable','email','exists:users,email','required_without:user_id'],
            'user_id' => ['nullable','exists:users,id','required_without:email'],
            'role'    => ['required','in:owner,editor,viewer'],
        ], [], ['email'=>'correo','user_id'=>'usuario','role'=>'rol']);

        $target = isset($data['email'])
            ? User::where('email',$data['email'])->first()
            : User::findOrFail($data['user_id']);

        // bloqueos
        if ($target->id === $request->user()->id) {
            return response()->json(['message'=>'No puedes invitarte a ti mismo.'], 422);
        }
        if ($target->id === $list->id_user) {
            return response()->json(['message'=>'No puedes invitar al propietario de la lista.'], 422);
        }

        // adjuntar/actualizar rol
        $list->members()->syncWithoutDetaching([$target->id => ['role'=>$data['role']]]);
        $list->members()->updateExistingPivot($target->id, ['role'=>$data['role']]);

        return response()->json(['ok'=>true]);
    }

    // PATCH /lists/{list}/members/{user}  (cambiar rol)
    public function update(Request $request, ListModel $list, User $user)
    {
        $this->authorize('manageMembers', $list); // propietario real o owner (pivot)

        $data = $request->validate([
            'role' => ['required','in:owner,editor,viewer'],
        ]);

        // no tocar al propietario real
        if ($user->id === $list->id_user) {
            return response()->json(['message'=>'No puedes modificar al propietario principal.'], 422);
        }

        $list->members()->syncWithoutDetaching([$user->id => ['role'=>$data['role']]]);
        $list->members()->updateExistingPivot($user->id, ['role'=>$data['role']]);

        return response()->json(['ok'=>true]);
    }

    // DELETE /lists/{list}/members/{user}  (quitar miembro)
    public function destroy(ListModel $list, User $user)
    {
        $this->authorize('manageMembers', $list); // propietario real o owner (pivot)

        // no eliminar al propietario real
        if ($user->id === $list->id_user) {
            return response()->json(['message'=>'No puedes eliminar al propietario principal.'], 422);
        }

        $list->members()->detach($user->id);

        return response()->json(['ok'=>true]);
    }
}
