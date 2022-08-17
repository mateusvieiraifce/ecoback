<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Language;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

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
            return view('auth/login',['msg'=>$msg] );
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
        return view('auth/register',['field'=>'']);
    }

    public function registreUserDo(Request $request){
        try {
            if ($this->valida($request,true)){
                $usuario = new User();
                $usuario->name = $request->name;
                $usuario->email = $request->email;
                $usuario->password = bcrypt($request->password);
                $usuario->save();
                Session::put(['status'=>'Operação realizada com sucesso!']);
                $dados =['email' => $request->email,'password' => $request->password];
                if (Auth::attempt($dados, false)) {
                    $request->session()->regenerate();
                    return redirect()->intended('home');
                }
            }
        }catch (QueryException $exp){
            dd($exp);
        }
        return view('auth/register',['field'=>'']);
    }

    function valida(Request  $request, $tipo){


        if ($tipo) {

            $variable = $request->password;
            $input = $request->validate([
                'name' => 'required|between :5,15',
                'password' => 'required|between :5,15',
                'password_confirmation' => 'required|between :5,15|in:'.$variable,
                'email' => 'required|unique:users,email',
                'aceito'=>'required',

            ]);
        }

        return $input;

    }
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/app');
        }
        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser){

            /*if ($existingUser->lang){
                $lang = Language::find($existingUser->lang);
                if ($lang) {
                    App::setLocale($lang->locale);
                }else{
                    App::setLocale("pt-br");
                }
            }else{
                App::setLocale("pt-br");
            }*/
            // log them in
            auth()->login($existingUser, true);

        } else {

            $userName = explode("@", $user->email)[0];
            $newUser                  = new User;
            $newUser->name            = $user->user['given_name']." ".$user->user['family_name'] ;
            $newUser->email           = $user->email;
            $newUser->google_id       = $user->id;
            $newUser->avatar          = $user->avatar;
            $newUser->avatar_original = $user->avatar_original;
            $newUser->type=2;
            $newUser->password=bcrypt('123456');
            $newUser->save();

            $msgemail = "Seja Bem Vindo a plataforma Stakehol, ".$user->name.
                ", esperamos contruibuir com sua jornada positivamente!<br>  acessse http://stakehol.herokuapp.com/app ".
                "Atenciosamente, Stakehol. ";
            Helper::sendEmail(trans("messport.welcome_plataform"),$msgemail,$user->email);

            auth()->login($newUser, true);
        }
        return redirect()->to('/home');
    }

    public function redirectToProvider()
    {

        return Socialite::driver('google')->redirect();
    }

}
