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
use App\Http\Controllers\CategoryController;

Route::resource('admin', AdminController::class);

use App\Http\Controllers\ProductController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/create-product', [ProductController::class, 'index'])->name('product.create.form');
    Route::post('/dashboard/create-product', [ProductController::class, 'create'])->name('product.create');
    
    Route::get('/dashboard/edit-product', [ProductController::class, 'editForm'])->name('product.edit.form');
    Route::get('/dashboard/edit-product/{id}', [ProductController::class, 'editForm'])->name('product.edit.single');
    Route::put('/dashboard/edit-product/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::post('/dashboard/products/{id}/images', [ProductController::class, 'updateImages'])->name('product.updateImages');
    Route::delete('/dashboard/products/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::put('/dashboard/products/{id}/toggle-status', [ProductController::class, 'toggleStatus'])->name('product.toggleStatus');
    
    Route::get('/dashboard/create-category', [CategoryController::class, 'index'])->name('category.create.form');
    Route::post('/dashboard/create-category', [CategoryController::class, 'create'])->name('category.create');

    Route::get('/dashboard/edit-category', [CategoryController::class, 'catEditForm'])->name('category.edit.form');
    Route::put('/dashboard/edit-category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/dashboard/edit-category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
});