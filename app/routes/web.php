<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/where', [HomeController::class, 'where'])->name('where');
Route::get('/catalog', [ProductController::class, 'catalog'])->name('catalog');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Cart (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::post('/cart/order', [CartController::class, 'order'])->name('cart.order');
});

// Orders (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::post('/orders/delete', [OrderController::class, 'destroy'])->name('orders.delete');
});

// Admin (is_admin required, checked in controller constructor)
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::post('/category/add', [AdminController::class, 'addCategory'])->name('admin.category.add');
    Route::post('/category/delete', [AdminController::class, 'deleteCategory'])->name('admin.category.delete');
    Route::post('/product/add', [AdminController::class, 'addProduct'])->name('admin.product.add');
    Route::post('/product/delete', [ProductController::class, 'destroy'])->name('admin.product.delete');
    Route::post('/order/status', [AdminController::class, 'updateOrderStatus'])->name('admin.order.status');
    Route::post('/order/delete', [AdminController::class, 'deleteOrder'])->name('admin.order.delete');
});

// Product edit
Route::middleware('auth')->group(function () {
    Route::get('/product/{product}/edit', [ProductController::class, 'editForm'])->name('product.edit');
    Route::post('/product/{product}/edit', [ProductController::class, 'update'])->name('product.update');
});
