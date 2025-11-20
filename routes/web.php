<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\{
    ProfileController,
    ListController,
    CategoryController,
    ProductController,
    CommentController,
    ListMemberController
};

use App\Models\ListModel;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard (protegido)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])
  ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Perfil de usuario
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| LISTAS (Blade)
|--------------------------------------------------------------------------
*/

// Página con todas las listas
Route::middleware('auth')->get('/lists', function () {
    $u = Auth::user();
    return view('lists.index', [
        'owned'  => $u->lists,           // listas propias
        'shared' => $u->sharedLists,     // listas compartidas
    ]);
})->name('lists.page');

// Página de detalle de una lista
Route::middleware('auth')->get('/lists/{list}', function (ListModel $list) {
    Gate::authorize('view', $list);
    $list->load(['owner','members','categories','products','comments.user']);
    return view('lists.show', compact('list'));
})->name('lists.show');


/*
|--------------------------------------------------------------------------
| ENDPOINTS JSON (todo sigue en web.php)
|--------------------------------------------------------------------------
*/

// LIST CRUD (crear, actualizar, borrar)
Route::middleware('auth')->post('/lists',        [ListController::class, 'store']);
Route::middleware('auth')->put('/lists/{list}',  [ListController::class, 'update']);
Route::middleware('auth')->delete('/lists/{list}', [ListController::class, 'destroy']);


// CATEGORÍAS
Route::middleware('auth')->post('/lists/{list}/categories',               [CategoryController::class, 'store']);
Route::middleware('auth')->delete('/lists/{list}/categories/{category}',  [CategoryController::class, 'destroy'] ?? function(){} );
Route::middleware('auth')->delete('/lists/{list}/categories/{category}',  [CategoryController::class, 'destroy']);


// PRODUCTOS
Route::middleware('auth')->post('/lists/{list}/products',               [ProductController::class, 'store']);
Route::middleware('auth')->patch('/lists/{list}/products/{product}/toggle', [ProductController::class, 'toggle']);
Route::middleware('auth')->delete('/lists/{list}/products/{product}',   [ProductController::class, 'destroy']);


// COMENTARIOS
Route::middleware('auth')->post('/lists/{list}/comments',     [CommentController::class, 'store']);
Route::middleware('auth')->delete('/comments/{comment}',      [CommentController::class, 'destroy']);


// COMPARTIR LISTAS
Route::middleware('auth')->post('/lists/{list}/members',         [ListMemberController::class, 'store']);
Route::middleware('auth')->patch('/lists/{list}/members/{user}', [ListMemberController::class, 'update']);
Route::middleware('auth')->delete('/lists/{list}/members/{user}',[ListMemberController::class, 'destroy']);


/*
|--------------------------------------------------------------------------
| Auth routes (login, register, logout)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
