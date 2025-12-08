<?php

namespace App\Http\Controllers;

use App\Models\{Category, ListModel};
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * POST /lists/{list}/categories
     * Crea una CATEGORÍA y la adjunta a la LISTA.
     * Body: { name: string }
     */
    public function store(Request $request, ListModel $list)
    {
        // Miembro (owner/editor) de la lista
        $this->authorize('createInList', [Category::class, $list]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        // Si quieres reutilizar por nombre globalmente, descomenta:
        // $category = Category::firstOrCreate(['name' => $data['name']]);

        $category = Category::firstOrCreate(['name' => $data['name']]);

        // Adjuntar a la lista sin duplicar
        $list->categories()->syncWithoutDetaching($category->id_category);

        return response()->json($category, 201);
    }

    /**
     * PUT /lists/{list}/categories/{category}
     * Renombra la CATEGORÍA (requiere pertenecer a la LISTA).
     * Body: { name: string }
     */
    public function update(Request $request, ListModel $list, Category $category)
    {
        // Miembro (owner/editor) de la lista
        $this->authorize('update', [$category, $list]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $category->update(['name' => $data['name']]);

        return response()->json($category);
    }

    /**
     * DELETE /lists/{list}/categories/{category}
     * Desvincula la categoría de la lista (NO borra la categoría global)
     */
    public function destroy(ListModel $list, Category $category)
    {
        // 1. Autorizar (owner/editor)
        $this->authorize('detachFromList', [Category::class, $list]);

        // 2. Verificar que la categoría pertenece a la lista
        if (!$list->categories->contains($category->id_category)) {
            return response()->json(['error' => 'Categoría no pertenece a la lista'], 403);
        }

        // 3. Eliminar productos de esa categoría
        $category->products()->delete();

        // 4. Quitar la categoría de la lista (desvincular)
        $list->categories()->detach($category->id_category);

        // 5. Si la categoría ya NO está en ninguna lista → borrarla totalmente
        if ($category->lists()->count() === 0) {
            $category->delete();
        }

        return response()->json(['ok' => true]);
    }
}
