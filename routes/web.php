<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/login', [UserController::class, 'login'])->name('login');

Route::post('/login', [UserController::class, 'loginPost'])->name('login.post');

Route::get('/register', [UserController::class, 'register'])->name('register');

Route::post('/register', [UserController::class, 'registerPost'])->name('register.post');

Route::get('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/details', [UserController::class, 'userDetails'])->name('details');

Route::get('/products', [ProductController::class,'getProducts'])->name('products');

Route::post('/cart/add/{productId}', [CartController::class, 'addCartItem'])->name('cart.post');

Route::get('/cart', [CartController::class, 'getCart'])->name('cart.show');

Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('clearCart');

Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');