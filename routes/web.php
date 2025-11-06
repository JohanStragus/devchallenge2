<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Http\Controllers\{
    ListController,
    ListMemberController,
    CategoryController,
    ListCategoryController,
    ProductController,
    ListProductController,
    CommentController
};


Route::get('/', function () {
    return view('welcome');
});

// 🔐 Asegura todas las rutas con autenticación
Route::middleware('auth')->group(function () {

    // === LISTAS PRINCIPALES ===
    Route::apiResource('lists', ListController::class);

    // === MIEMBROS DE LISTA ===
    Route::prefix('lists/{list}')->group(function () {
        Route::post('/members', [ListMemberController::class, 'store']);
        Route::patch('/members/{user}', [ListMemberController::class, 'update']);
        Route::delete('/members/{user}', [ListMemberController::class, 'destroy']);
    });

    // === CATEGORÍAS ===
    Route::prefix('lists/{list}')->group(function () {
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::post('/categories/attach', [ListCategoryController::class, 'attach']);
        Route::delete('/categories/{category}', [ListCategoryController::class, 'detach']);
    });

    // === PRODUCTOS ===
    Route::prefix('lists/{list}')->group(function () {
        Route::post('/products', [ProductController::class, 'store']);
        Route::patch('/products/{product}/toggle', [ProductController::class, 'toggle']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        Route::post('/products/{product}/attach', [ListProductController::class, 'attach']);
        Route::delete('/products/{product}/detach', [ListProductController::class, 'detach']);
        Route::post('/products/attach-bulk', [ListProductController::class, 'attachBulk']);
    });

    // === COMENTARIOS ===
    Route::prefix('lists/{list}')->group(function () {
        Route::get('/comments', [CommentController::class, 'index']);
        Route::post('/comments', [CommentController::class, 'store']);
    });

    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
});
