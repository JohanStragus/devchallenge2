<?php

namespace App\Http\Controllers;

use App\Models\{ListModel, Category};
use Illuminate\Http\Request;

class ListCategoryController extends Controller
{
    /**
     * POST /lists/{list}/categories/attach
     * Adjunta una categoría EXISTENTE a la lista (sin duplicar).
     * Body: { id_category: int }
     */
    public function attach(Request $request, ListModel $list)
    {
        // Política: solo miembros (owner/editor) de la lista
        $this->authorize('attachToList', [Category::class, $list]);

        $data = $request->validate([
            'id_category' => ['required', 'exists:categories,id_category'],
        ], [], [
            'id_category' => 'categoría',
        ]);

        // Evita duplicados
        $list->categories()->syncWithoutDetaching($data['id_category']);

        return response()->json(['ok' => true]);
    }

    /**
     * DELETE /lists/{list}/categories/{category}
     * Quita la categoría de la lista.
     */
    public function detach(ListModel $list, Category $category)
    {
        // Política: solo miembros (owner/editor) de la lista
        $this->authorize('detachFromList', [Category::class, $list]);

        $list->categories()->detach($category->id_category);

        return response()->json(['ok' => true]);
    }
}
