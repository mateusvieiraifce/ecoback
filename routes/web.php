<?php

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

Route::get("/app",[\App\Http\Controllers\UsuarioController::class,'preLogin'])->name('login');
Route::get("/registre",[\App\Http\Controllers\UsuarioController::class,'registreUser'])->name('registre');
Route::post("/registre",[\App\Http\Controllers\UsuarioController::class,'registreUserDo'])->name('registre');

Route::post("/app",[\App\Http\Controllers\UsuarioController::class,'logar'])->name('login.do');
Route::get("/logout",[\App\Http\Controllers\UsuarioController::class,'logout'])->name('logout');


Route::get('/', function () {
    return view('dashboard');
});

Route::get('/home', function () {
    return view('dashboard');
})->name('home');

Route::get('/users/', function () {
    return view('users/index');
})->name('profile.edit');

Route::get('/users/index', function () {
    return view('users/index');
})->name('user.index');
