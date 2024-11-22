<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
// Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');

// Route::get('/products', [ProductController::class, 'index'])->name('products.index');
// Route::post('/products', [ProductController::class, 'store'])->name('products.store');
// Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
// Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
// Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::get('products-list', [ProductController::class, 'showByPage'])->name('products.showByPage');
    Route::post('logout', [UserController::class, 'logout'])->name('logout')->middleware(middleware: 'auth:sanctum');
});

Route::post('login', [UserController::class, 'login'])->name('login');
Route::post('register', [UserController::class, 'register'])->name('register');
