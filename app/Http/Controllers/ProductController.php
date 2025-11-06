<?php

namespace App\Http\Controllers;

use App\Models\{Product, ListModel};
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * POST /lists/{list}/products
     * Crea un producto y lo asocia a una lista.
     * Body: { name: string, id_category: int }
     */
    public function store(Request $request, ListModel $list)
    {
        $this->authorize('create', [Product::class, $list]);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'id_category' => ['required', 'exists:categories,id_category'],
        ]);

        $product = Product::create([
            'id_category' => $data['id_category'],
            'name'        => $data['name'],
            'completed'   => false,
        ]);

        // Asociar el producto a la lista
        $list->products()->syncWithoutDetaching($product->id_product);

        return response()->json($product, 201);
    }

    /**
     * PATCH /lists/{list}/products/{product}/toggle
     * Cambia el estado completed (true/false).
     */
    public function toggle(ListModel $list, Product $product)
    {
        $this->authorize('toggle', [Product::class, $list]);

        $product->update(['completed' => ! $product->completed]);

        return response()->json($product);
    }

    /**
     * PUT /lists/{list}/products/{product}
     * Edita el nombre o categoría del producto.
     * Body: { name?: string, id_category?: int }
     */
    public function update(Request $request, ListModel $list, Product $product)
    {
        $this->authorize('update', [Product::class, $list]);

        $data = $request->validate([
            'name'        => ['nullable', 'string', 'max:255'],
            'id_category' => ['nullable', 'exists:categories,id_category'],
        ]);

        $product->update(array_filter($data)); // solo campos enviados

        return response()->json($product);
    }

    /**
     * DELETE /lists/{list}/products/{product}
     * Elimina el producto de la lista (detach o delete).
     */
    public function destroy(ListModel $list, Product $product)
    {
        $this->authorize('delete', [Product::class, $list]);

        // Si solo quieres desvincular de la lista (manteniendo producto global):
        $list->products()->detach($product->id_product);

        // Si prefieres borrarlo completamente de la DB:
        // $product->delete();

        return response()->json(['deleted' => true]);
    }
}
