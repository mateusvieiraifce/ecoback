<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    function preLogin(){
        return view('auth/login',['pageSlug'=>'']);
    }

    function logar(Request  $request){

        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);


        $dados =['email' => $request->email,'password' => $request->password];
        if (Auth::attempt($dados, false)) {
            $request->session()->regenerate();
            return redirect()->intended('home');
        } else{

            $msg = ['valor'=>trans('messport.login.fail'),'tipo'=>'primary'];
            return view('login',['msg'=>$msg] );
        }

        return view('auth/login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/app');
    }

    public function registreUser(){
        return view('auth/register');
    }

    public function registreUserDo(Request $request){
        try {
            $usuario = new User();
            $usuario->name = $request->name;
            $usuario->email = $request->email;
            $usuario->password = bcrypt($request->password);
            $usuario->save();
        }catch (QueryException $exp){
            dd($exp);
        }
        return view('auth/register');
    }

}
