<?php

use Illuminate\Support\Facades\Auth;
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
    return view('frente/index');
})->name('home.frente');


Route::get('/sobre', function () {
    return view('frente/about');
})->name('home.sobre');


Route::get('/index', function () {
    return view('frente/index');
})->name('index');

Route::get('/produtos', function () {
    return view('frente/produtos');
})->name('produtos');

Route::get('/contato', function () {
    return view('frente/contato');
})->name('contato');

Route::get('/checkout', [\App\Http\Controllers\CheckoutControler::class,"checkout"])->name('finalizar');

Route::post('/mail',[\App\Http\Controllers\MailController::class,"sendMail"])->name('sendmail');

Route::get("/app",[\App\Http\Controllers\UsuarioController::class,'preLogin'])->name('login');
Route::get("/registre",[\App\Http\Controllers\UsuarioController::class,'registreUser'])->name('registre');
Route::post("/registre",[\App\Http\Controllers\UsuarioController::class,'registreUserDo'])->name('registre');
Route::post("/app",[\App\Http\Controllers\UsuarioController::class,'logar'])->name('login.do');
Route::get("/logout",[\App\Http\Controllers\UsuarioController::class,'logout'])->name('logout');
Route::get("/recuperar",[\App\Http\Controllers\UsuarioController::class,'recover'])->name('recover');
Route::get("/recuperar/{id?}",[\App\Http\Controllers\UsuarioController::class,'recoverID'])->name('recover.id');
Route::post("/recuperar",[\App\Http\Controllers\UsuarioController::class,'recoverDo'])->name('recover.do');
Route::post("/updatepassword",[\App\Http\Controllers\UsuarioController::class,'recoverPassword'])->name('update.password');
Route::get("/profile/{id?}",[\App\Http\Controllers\UsuarioController::class,'preEdit'])->name('user.preedit')->middleware('auth');
Route::get("/turnvendedor",[\App\Http\Controllers\UsuarioController::class,'turnVendedor'])->name('user.turnvendedor')->middleware('auth');

Route::post("/profile/update",[\App\Http\Controllers\UsuarioController::class,'update'])->name('user.update');
Route::put("/profile/update",[\App\Http\Controllers\UsuarioController::class,'updateCompletar'])->name('user.update.comp');
Route::post("/profile/delete",[\App\Http\Controllers\UsuarioController::class,'delete'])->name('user.delete');
Route::get("/profile/update/add",[\App\Http\Controllers\UsuarioController::class,'addEndereco'])->name('user.update.add');
Route::post("/profile/update/add",[\App\Http\Controllers\UsuarioController::class,'addEnderecoDo'])->name('user.update.add.do');
Route::get("/profile/endereco/del/{id}",[\App\Http\Controllers\UsuarioController::class,'delEndereco'])->name('user.update.del.do');
Route::get("/profile/endereco/principal/{id}",[\App\Http\Controllers\UsuarioController::class,'setPrincialEndereco'])->name('user.update.end.pri');
Route::get("/profile/update/add/{id}",[\App\Http\Controllers\UsuarioController::class,'addEndereco'])->name('user.add.update');

Route::get("/advertisement/",[\App\Http\Controllers\AnuncioController::class,'list'])->name('advertisement.list')->middleware('auth');;
Route::get("/advertisement/add",[\App\Http\Controllers\AnuncioController::class,'add'])->name('advertisement.add')->middleware('auth');;
Route::post("/advertisement/save",[\App\Http\Controllers\AnuncioController::class,'save'])->name('advertisement.save')->middleware('auth');;
Route::get("/advertisement/delete/{id}",[\App\Http\Controllers\AnuncioController::class,'delete'])->name('advertisement.delete')->middleware('auth');;
Route::get("/advertisement/edit/{id}",[\App\Http\Controllers\AnuncioController::class,'edit'])->name('advertisement.edit')->middleware('auth');;


Route::get("/favorite/add/{id}",[\App\Http\Controllers\AnuncioController::class,'addFavorite'])->name('advertisement.addfavorito');
Route::get("/favorite/list",[\App\Http\Controllers\AnuncioController::class,'listFavorite'])->name('advertisement.listfavorito');
Route::get("/favorite/remove/{id}",[\App\Http\Controllers\AnuncioController::class,'remFavorite'])->name('advertisement.remfavorito');

/*TODO REFACTORY TO FRONT */
Route::get("/detail/{id}",[\App\Http\Controllers\AnuncioController::class,'produtctDetail'])->name('advertisement.detail');
Route::post("/comentario/add/",[\App\Http\Controllers\AnuncioController::class,'addComentario'])->name('advertisement.comentario.add');

Route::get("/cart/add/",[\App\Http\Controllers\AnuncioController::class,'addSession'])->name('advertisement.addsession');
Route::get("/cart/view/",[\App\Http\Controllers\AnuncioController::class,'viewSession'])->name('advertisement.viewssesion');
Route::get("/cart/clear/",[\App\Http\Controllers\AnuncioController::class,'clearCarr'])->name('cart.clear');
Route::get("/cart/remqnt/{id}",[\App\Http\Controllers\CheckoutControler::class,'removerQntCarrinho'])->name('cart.remqtd');
Route::get("/cart/addqnt/{id}",[\App\Http\Controllers\CheckoutControler::class,'addQntCarrinho'])->name('cart.addqtd');


Route::get('/redirect', '\App\Http\Controllers\UsuarioController@redirectToProvider')->name('google.redi');
Route::get('/callback', '\App\Http\Controllers\UsuarioController@handleProviderCallback')->name('google.callback');;


Route::post("/checkout/create",[\App\Http\Controllers\CheckoutControler::class,'create'])->name('vendas.create')->middleware('auth');;
Route::get("/checkout/confirmpay/{id}",[\App\Http\Controllers\CheckoutControler::class,'posProcessPagamento'])->name('vendas.payment');


Route::get('/minhaarea', function () {
    return view('dashboard');
})->middleware('auth');;

Route::get('/home', function () {
    $usuario = Auth::user();
    $compras = \App\Models\Vendas::where('comprador_id','=',$usuario->id)->orderBy('created_at','desc')->get();
    return view('dashboard',['compras'=>$compras]);

})->name('home')->middleware('auth');;

Route::get('/compras', function () {
    $usuario = Auth::user();
    $compras = \App\Models\Vendas::where('comprador_id','=',$usuario->id)->orderBy('created_at','desc')->get();
    return view('dashboard',['compras'=>$compras]);

})->name('compras.list')->middleware('auth');;


Route::get('/users/', function () {
    return view('users/index');
})->name('profile.edit')->middleware('auth');;

Route::get('/users/index', function () {
    return view('users/index');
})->name('user.index');
