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

        $category = Category::create(['name' => $data['name']]);

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
}
