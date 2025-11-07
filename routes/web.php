<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Models\{ListModel, Category, Product, Comment};
use Illuminate\Support\Facades\Auth;

// NUEVO: importamos controladores de categorías
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ListCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ListProductController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ListMemberController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // perfil Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // LISTAS
    Route::get('/lists', function () {
        /** @var User $u */
        $u = Auth::user();

        return view('lists.index', [
            'owned'  => ListModel::where('id_user', $u->id)->get(),
            'shared' => $u->sharedLists()->get(),
        ]);
    })->name('lists.page');

    Route::get('/lists/{list}', function (ListModel $list) {
        Gate::authorize('view', $list);
        $list->load(['owner','members','categories','products','comments.user']);
        return view('lists.show', compact('list'));
    })->name('lists.show');


// ESTE APARTADO ES PARA PROBAR LO PUEDES CAMBIAR SERGIOOOOOOOOOOOOOOOOOOOOOOOOOO

    // ✅ CATEGORÍAS
    Route::post('/lists/{list}/categories', [CategoryController::class, 'store']);
    Route::put('/lists/{list}/categories/{category}', [CategoryController::class, 'update']);
    Route::post('/lists/{list}/categories/attach', [ListCategoryController::class, 'attach']);
    Route::delete('/lists/{list}/categories/{category}', [ListCategoryController::class, 'detach']);


    // ✅ PRODUCTOS
Route::post('/lists/{list}/products', [ProductController::class, 'store']);
Route::patch('/lists/{list}/products/{product}/toggle', [ProductController::class, 'toggle']);
Route::put('/lists/{list}/products/{product}', [ProductController::class, 'update']);
Route::delete('/lists/{list}/products/{product}', [ProductController::class, 'destroy']);

Route::post('/lists/{list}/products/{product}/attach', [ListProductController::class, 'attach']);
Route::delete('/lists/{list}/products/{product}/detach', [ListProductController::class, 'detach']);
Route::post('/lists/{list}/products/attach-bulk', [ListProductController::class, 'attachBulk']);

// ✅ COMENTARIOS
Route::get('/lists/{list}/comments', [CommentController::class, 'index']); 
Route::post('/lists/{list}/comments', [CommentController::class, 'store']);
Route::put('/comments/{comment}', [CommentController::class, 'update']);
Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

// Miembros (compartir lista)
Route::post('/lists/{list}/members', [ListMemberController::class, 'store']);
Route::patch('/lists/{list}/members/{user}', [ListMemberController::class, 'update']);
Route::delete('/lists/{list}/members/{user}', [ListMemberController::class, 'destroy']);

    // LISTS API
    Route::apiResource('lists', App\Http\Controllers\ListController::class)
    ->only(['store','update','destroy']);
});

// NO BORRAR
require __DIR__.'/auth.php';
