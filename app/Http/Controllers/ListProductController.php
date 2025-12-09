<?php

namespace App\Http\Controllers;

use App\Models\{ListModel, Product};
use Illuminate\Http\Request;

class ListProductController extends Controller
{
    /**
     * POST /lists/{list}/products/{product}/attach
     * Vincula un PRODUCTO EXISTENTE a la LISTA (sin duplicar).
     */
    public function attach(ListModel $list, Product $product)
    {
        $this->authorize('attach', [Product::class, $list]);

        $list->products()->syncWithoutDetaching($product->id_product);

        return response()->json(['ok' => true]);
    }

    /**
     * DELETE /lists/{list}/products/{product}
     * Quita (detach) el PRODUCTO de la LISTA.
     */
    public function detach(ListModel $list, Product $product)
    {
        $this->authorize('delete', [Product::class, $list]);

        $list->products()->detach($product->id_product);

        return response()->json(['ok' => true]);
    }

    /**
     * POST /lists/{list}/products/attach-bulk
     * Adjunta varios productos a la vez.
     */
    public function attachBulk(Request $request, ListModel $list)
    {
        $this->authorize('attach', [Product::class, $list]);

        $data = $request->validate([
            'ids'   => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:products,id_product'],
        ], [], ['ids' => 'productos']);

        $ids = $data['ids'];
        // Construimos pares [id_product => []] para syncWithoutDetaching
        $pairs = array_fill_keys($ids, []);

        $list->products()->syncWithoutDetaching($pairs);

        return response()->json(['ok' => true, 'attached' => $ids]);
    }
}
