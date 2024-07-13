<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IngredientController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Route::get('/register', [UserController::class, 'register']);
// Route::get('/forgot', [UserController::class, 'forgot']);
// Route::get('/reset', [UserController::class, 'reset']);

// Route::post('/register-user', [UserController::class, 'saveUser'])->name('auth.register');
// Route::post('/login', [UserController::class, 'loginUser'])->name('auth.login');



// Route::group(['middleware' => ['LoginCheck']], function (){

//     Route::get('/profile', [UserController::class, 'profile'])->name('profile');
//     Route::get('/', [UserController::class, 'index']);
//     Route::get('/logout', [UserController::class, 'logout'])->name('auth.logout');
// });

Route::get('/ingredient', [IngredientController::class, 'ingredient']);
Route::post('/store', [IngredientController::class, 'store'])->name('store');
Route::get('/fetchAll', [IngredientController::class, 'fetchAll'])->name('fetchAll');
Route::delete('/delete', [IngredientController::class, 'delete'])->name('delete');
Route::get('/edit', [IngredientController::class, 'edit'])->name('edit');
Route::post('/update', [IngredientController::class, 'update'])->name('update');


Route::view('/ingredient-all', 'ingredient.index');

// Route::middleware(['LoginCheck'])->group(function () {
//     Route::get('/', [UserController::class, 'index'])->name('auth.login');
//     Route::get('/register', [UserController::class, 'register'])->name('auth.register');
//     Route::post('/register', [UserController::class, 'registerUser']);
//     Route::post('/login', [UserController::class, 'loginUser'])->name('login');
//     Route::get('/profile', [UserController::class, 'profile'])->name('profile');
//     Route::post('/logout', [UserController::class, 'logout'])->name('auth.logout');
// });


// Auth routes
Route::get('/', [UserController::class, 'index'])->name('auth.login');
Route::get('/register', [UserController::class, 'register'])->name('auth.register');
Route::post('/register', [UserController::class, 'registerUser']);
Route::post('/login', [UserController::class, 'loginUser'])->name('login');
Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('auth.logout');
});