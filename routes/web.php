<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('landing');

use App\Http\Controllers\TestingController;
Route::get('/test-image-uuid', [TestingController::class, 'createNoProduct']);

Route::get('/login', function() {
    return view('auth.login');
})->name('login');

use App\Http\Controllers\UserController;
Route::post('/login', [UserController::class, 'login'])->name('login.post');
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard')->middleware('auth');

use App\Http\Controllers\AdminController;
Route::resource('admin', AdminController::class);

use App\Http\Controllers\ProductController;
Route::get('/dashboard/create-product', [ProductController::class, 'index'])->name('product.index');
Route::post('/dashboard/create-product', [ProductController::class, 'create'])->name('product.create');
Route::get('/dashboard/edit-product', [ProductController::class, 'editForm'])->name('product.editForm');
Route::get('/dashboard/edit-product/{id}', [ProductController::class, 'editForm'])->name('product.editForm');
Route::put('/dashboard/edit-product/{id}', [ProductController::class, 'update'])->name('product.update');
Route::post('/dashboard/products/{id}/images', [ProductController::class, 'updateImages'])->name('product.updateImages');