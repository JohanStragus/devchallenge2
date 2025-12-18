<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

use Laravel\Socialite\Facades\Socialite; // ← Google SSO

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
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| LOGIN CON GOOGLE (SSO)
|--------------------------------------------------------------------------
*/

Route::get('/google/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('/google/callback', function () {

    /** @var \Laravel\Socialite\Two\GoogleProvider $driver */
    $driver = Socialite::driver('google');
    $googleUser = $driver->stateless()->user();


    // Crear o actualizar usuario según email
    $user = \App\Models\User::updateOrCreate(
        ['email' => $googleUser->email],
        [
            'name'     => $googleUser->name,
            'password' => null, // usuarios Google no necesitan pass
        ]
    );

    Auth::login($user, true);

    return redirect()->route('dashboard');
});


/*
|--------------------------------------------------------------------------
| DASHBOARD (USUARIO AUTENTICADO)
| - Punto principal después del login
| - Aquí se muestran las listas del usuario (propias y compartidas)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->get('/dashboard', function () {

    $u = Auth::user();

    return view('dashboard', [
        'owned'  => $u->lists,
        'shared' => $u->sharedLists,
    ]);
})->name('dashboard');



/*
|--------------------------------------------------------------------------
| PERFIL DE USUARIO
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



/*
|--------------------------------------------------------------------------
| DETALLE DE UNA LISTA (VISTA BLADE)
| - Ejemplo: /lists/2
| - El listado principal está en /dashboard
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->get('/lists/{list}', function (ListModel $list) {

    Gate::authorize('view', $list);

    $list->load([
        'owner',
        'members',
        'categories',
        'products',
        'comments.user',
    ]);

    return view('lists.show', compact('list'));
})->name('lists.show');



/*
|--------------------------------------------------------------------------
| REDIRECCIÓN /lists → /dashboard
| - Evita error 405 al hacer GET /lists
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->get('/lists', function () {
    return redirect()->route('dashboard');
});



/*
|--------------------------------------------------------------------------
| ENDPOINTS JSON: CRUD DE LISTAS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->post('/lists',          [ListController::class, 'store']);
Route::middleware('auth')->put('/lists/{list}',    [ListController::class, 'update']);
Route::middleware('auth')->delete('/lists/{list}', [ListController::class, 'destroy']);



/*
|--------------------------------------------------------------------------
| ENDPOINTS JSON: CATEGORÍAS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->post('/lists/{list}/categories',              [CategoryController::class, 'store']);
Route::middleware('auth')->put('/lists/{list}/categories/{category:id_category}', [CategoryController::class, 'update']);
Route::middleware('auth')->delete('/lists/{list}/categories/{category:id_category}', [CategoryController::class, 'destroy']);



/*
|--------------------------------------------------------------------------
| ENDPOINTS JSON: PRODUCTOS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->post('/lists/{list}/products',                   [ProductController::class, 'store']);
Route::middleware('auth')->put('/lists/{list}/products/{product:id_product}', [ProductController::class, 'update']);
Route::middleware('auth')->patch('/lists/{list}/products/{product}/toggle', [ProductController::class, 'toggle']);
Route::middleware('auth')->delete('/lists/{list}/products/{product}',       [ProductController::class, 'destroy']);



/*
|--------------------------------------------------------------------------
| ENDPOINTS JSON: COMENTARIOS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->post('/lists/{list}/comments', [CommentController::class, 'store']);
Route::middleware('auth')->delete('/comments/{comment}',  [CommentController::class, 'destroy']);



/*
|--------------------------------------------------------------------------
| ENDPOINTS JSON: COMPARTIR LISTAS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->post('/lists/{list}/members',          [ListMemberController::class, 'store']);
Route::middleware('auth')->patch('/lists/{list}/members/{user}',  [ListMemberController::class, 'update']);
Route::middleware('auth')->delete('/lists/{list}/members/{user}', [ListMemberController::class, 'destroy']);



/*
|--------------------------------------------------------------------------
| AUTH ROUTES (LOGIN / REGISTER / LOGOUT)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
