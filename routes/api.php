<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
Route::post('/login',[RegisterController::class, 'login'])->name('login')->name('login');
Route::post('/register',[RegisterController::class, 'register'])->name('register');

Route::middleware('auth:api')->group(function(){
    Route::post('/profile', [RegisterController::class, 'profile'])->name('profile');
    Route::post('/logout', [RegisterController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::post('/add', [HomeController::class, 'store'])->name('add.ex');
    Route::put('/update', [HomeController::class, 'update'])->name('edit.ex');
    Route::delete('/delete', [HomeController::class, 'delete'])->name('delete.ex');
    Route::get('/show/{id}', [HomeController::class, 'show'])->name('single.ex');

    Route::prefix('group')->group(function(){
        Route::get('/all', [GroupController::class, 'index']);
        Route::get('/{id}', [GroupController::class, 'show']);
        Route::delete('delete/{id}', [GroupController::class, 'delete']);
        Route::put('edit/{id}', [GroupController::class, 'edit']);
        Route::post('/create', [GroupController::class, 'store']);
        
        Route::prefix('detail')->group(function(){
            Route::post('add/{id}', [GroupController::class, 'storeDetails']);
            Route::delete('delete/{id}', [GroupController::class, 'deleteDetail']);
            Route::put('edit/{id}', [GroupController::class, 'editDetail']);
        });
    });
    Route::get('get-total/{group_id}', [GroupController::class, 'calulate']);
    Route::get('markaspaid/{id}', [GroupController::class, 'markAsPaid']);
    // Route::resource('/products', ProductController::class);
});