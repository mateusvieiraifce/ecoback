<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Language;
use App\Models\PasswordResets;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class UsuarioController extends Controller
{
    function preLogin(){
        return view('auth/login',['pageSlug'=>'']);
    }
    function recover($id=null){
        return view('auth/passwords/email',['pageSlug'=>'']);
    }

    function recoverDo(Request $request){
        $existe = User::where('email','=',$request->email)->first();
        if ($existe){
            $utimoreset = PasswordResets::where('email','=',$request->email)->orderBy('created_at')->first();
            $today = \DateTime::createFromFormat('d/m/y',date('d/m/y'));
            if ($utimoreset){
                $datacreat = \DateTime::createFromFormat('Y-m-d H:i:s', $utimoreset->created_at);
                $datacreat = $datacreat->add(new \DateInterval('P2D')); // 2 dias
                if ($datacreat<$today){
                    $utimoreset->delete();
                    $token =  $this->createToken($request,$today);
                }else{
                    $token = $utimoreset->token;
                }
            }else{
                $token =  $this->createToken($request,$today);
            }
            $msgemail = " <br>Para recuperar sua conta, acesse o link, ".
                ", acessse  ".env('URL_RECOVER').$token.
                " Atenciosamente, Ecomoda. ";
            Helper::sendEmail("Recuperação de senha da  Plataforma Ecomoda",$msgemail,$request->email);

        }else{
            $variable="Não existe";
            $input = $request->validate(['email' => 'in:'.$variable]);
        }
       return view('auth/passwords/email',['pageSlug'=>'','token'=>$token]);
    }

    function recoverID($id=null){
        return view('auth/passwords/reset',['pageSlug'=>'','token'=>'$token']);
    }

    private function  createToken(Request $request,$today){
        $token = Str::random(60);
        $re = new PasswordResets();
        $re->email= $request->email;
        $re->token = $token;
        $re->created_at = $today;
        $re->save();
        return $re->token;
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
                $this->sendEmailCreate($usuario);
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
            $this->sendEmailCreate($newUser);
            auth()->login($newUser, true);
        }
        return redirect()->to('/home');
    }

    private function sendEmailCreate($user){
        $msgemail = "Seja Bem Vindo a plataforma Ecomoda, ".$user->name.
            ", esperamos contruibuir com sua jornada positivamente!<br>  acessse http://ecoback.herokuapp.com/ ".
            "Atenciosamente, Ecomoda. ";
        Helper::sendEmail("Bem vindo a Plataforma Ecomoda",$msgemail,$user->email);
    }

    public function redirectToProvider()
    {

        return Socialite::driver('google')->redirect();
    }

}
