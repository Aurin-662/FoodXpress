<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

// ---------- Public routes ----------
Route::get('/', [HomeController::class, 'my_home']);
Route::get('/home', [HomeController::class, 'index']);
Route::post('/book_table', [HomeController::class, 'book_table']);

// Forget stored phone cookie
Route::get('/forget-phone', function () {
    return redirect()->back()->withCookie(Cookie::forget('last_phone'));
});

// ---------- Logged-in user routes (cart, order) ----------
Route::middleware(['loggedin'])->group(function () {
    Route::post('/add_cart/{id}', [HomeController::class, 'add_cart']);
    Route::post('/update_cart/{id}', [HomeController::class, 'update_cart']);
    Route::post('/add_review/{foodId}', [HomeController::class, 'add_review']);
    Route::get('/my_cart', [HomeController::class, 'my_cart']);
    Route::get('/remove_cart/{id}', [HomeController::class, 'remove_cart']);
//  Route::post('/confirm_order', [HomeController::class, 'confirm_order']);
    Route::post('/confirm_order', [HomeController::class, 'confirm_order'])
    ->middleware(['loggedin', 'delivery:Dhaka']);
    Route::get('/my_orders', [HomeController::class, 'my_orders']);

});

// ---------- Admin-only routes ----------
Route::middleware(['loggedin', 'admin'])->group(function () {
    Route::get('/add_food', [AdminController::class, 'add_food']);
    Route::post('/upload_food', [AdminController::class, 'upload_food']);
    Route::get('/view_food', [AdminController::class, 'view_food']);
    Route::get('/delete_food/{id}', [AdminController::class, 'delete_food']);
    Route::get('/update_food/{id}', [AdminController::class, 'update_food']);
    Route::post('/edit_food/{id}', [AdminController::class, 'edit_food']);

    Route::get('/orders', [AdminController::class, 'orders']);
    Route::get('/on_the_way/{id}', [AdminController::class, 'on_the_way']);
    Route::get('/delivered/{id}', [AdminController::class, 'delivered']);
    Route::get('/canceled/{id}', [AdminController::class, 'canceled']);
    Route::get('/reservations', [AdminController::class, 'reservations']);
    Route::get('/reviews', [AdminController::class, 'reviews']);

    Route::get('/admin_dashboard', [AdminController::class, 'dashboard']);

    Route::get('/sales_report', [AdminController::class, 'salesReport']);


    Route::get('/categories', [AdminController::class, 'categories']);
    Route::post('/add_category', [AdminController::class, 'addCategory']);
    Route::get('/delete_category/{id}', [AdminController::class, 'deleteCategory']);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});