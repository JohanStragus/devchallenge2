<?php

namespace App\Http\Controllers;

use App\Models\{Comment, ListModel};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * GET /lists/{list}/comments
     * Lista los comentarios de una lista (solo miembros/owner pueden verlos).
     */
    public function index(ListModel $list)
    {
        $this->authorize('view', [Comment::class, $list]);

        return response()->json($list->comments()->latest()->get());
    }

    /**
     * POST /lists/{list}/comments
     * Crea un comentario en la lista.
     */
    public function store(Request $request, ListModel $list)
    {
        $this->authorize('create', [Comment::class, $list]);

        $data = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $comment = Comment::create([
            'id_list' => $list->id,
            'id_user' => Auth::id(),
            'content' => $data['content'],
        ]);

        return response()->json($comment, 201);
    }

    /**
     * PUT /comments/{comment}
     * Edita el comentario (autor u owner de la lista).
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $data = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $comment->update(['content' => $data['content']]);

        return response()->json($comment);
    }

    /**
     * DELETE /comments/{comment}
     * Elimina el comentario (autor u owner de la lista).
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(['deleted' => true]);
    }
}
