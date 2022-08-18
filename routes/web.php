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
Route::get("/recuperar",[\App\Http\Controllers\UsuarioController::class,'recover'])->name('recover');
Route::get("/recuperar/{id?}",[\App\Http\Controllers\UsuarioController::class,'recoverID'])->name('recover.id');
Route::post("/recuperar",[\App\Http\Controllers\UsuarioController::class,'recoverDo'])->name('recover.do');
Route::post("/updatepassword",[\App\Http\Controllers\UsuarioController::class,'recoverPassword'])->name('update.password');
Route::get("/profile/{id?}",[\App\Http\Controllers\UsuarioController::class,'preEdit'])->name('user.preedit');
Route::post("/profile/update",[\App\Http\Controllers\UsuarioController::class,'update'])->name('user.update');
Route::post("/profile/delete",[\App\Http\Controllers\UsuarioController::class,'delete'])->name('user.delete');


Route::get('/redirect', '\App\Http\Controllers\UsuarioController@redirectToProvider')->name('google.redi');
Route::get('/callback', '\App\Http\Controllers\UsuarioController@handleProviderCallback')->name('google.callback');;



Route::get('/', function () {
    return view('dashboard');
})->middleware('auth');;

Route::get('/home', function () {
    return view('dashboard');
})->name('home')->middleware('auth');;

Route::get('/users/', function () {
    return view('users/index');
})->name('profile.edit')->middleware('auth');;

Route::get('/users/index', function () {
    return view('users/index');
})->name('user.index');
