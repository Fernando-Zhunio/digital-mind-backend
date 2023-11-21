<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect('admin/users');
});

Route::get('/', function () {
    return view('admin.users.index');
});

Route::prefix('admin')->group(function () {
    Route::get('/users', [UserController::class,'index']);
    Route::get('/users/create', [UserController::class,'create']);
    Route::post('/users', [UserController::class,'store']);
    Route::put('/users/{user}', [UserController::class,'update']);
    Route::delete('/users/{user}', [UserController::class,'destroy']);
});


