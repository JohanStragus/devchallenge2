<?php

namespace App\Http\Controllers;

use App\Models\ListModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class ListController extends Controller
{
    // GET /lists → listas propias y compartidas
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $owned  = ListModel::where('id_user', $user->id)->get();
        $shared = $user->sharedLists()->get();

        return response()->json([
            'owned'  => $owned,
            'shared' => $shared,
        ]);
    }

    // POST /lists  → crear lista
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $list = ListModel::create([
            'id_user' => Auth::id(),
            'name'    => $data['name'],
        ]);

        return response()->json($list, 201);
    }

    // GET /lists/{list}  → ver una lista (con relaciones)
    public function show(ListModel $list)
    {
        $this->authorize('view', $list);

        return response()->json(
            $list->load(['owner', 'members', 'categories', 'products', 'comments'])
        );
    }

    // PUT /lists/{list}  → renombrar
    public function update(Request $request, ListModel $list)
    {
        $this->authorize('update', $list);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $list->update(['name' => $data['name']]);

        return response()->json($list);
    }

    // DELETE /lists/{list}  → eliminar (solo owner)
    public function destroy(ListModel $list)
    {
        $this->authorize('delete', $list);

        $list->delete();

        return response()->json(['deleted' => true]);
    }
}