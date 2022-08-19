<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Endereco;
use App\Models\Language;
use App\Models\PasswordResets;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $msgret = ['valor'=>"Operação realizada com sucesso!",'tipo'=>'success'];
        $token = "";
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
            //$msg = ['valor'=>trans('messport.pass_conf'),'tipo'=>'primary'];
            $msgret = ['valor'=>"Usuário não existe",'tipo'=>'danger'];
        }

       return view('auth/passwords/email',['pageSlug'=>'','token'=>$token,'msg'=>$msgret]);
    }

    function recoverID($id=null){
        $voucher = PasswordResets::where('token','=',$id)->first();
        $msgret =null;
        $user = null;
        if ($voucher){
            $user = User::where('email','=',$voucher->email)->first();

        }else{
            $msgret = ['valor'=>"Usuário não existe",'tipo'=>'danger'];
        }

        return view('auth/passwords/reset',['pageSlug'=>'','token'=>'$token','msg'=>$msgret,'usuario'=>$user]);
    }

    function recoverPassword(Request $request){
        $msgret = ['valor'=>"Operação realizada com sucesso!",'tipo'=>'success'];
        $usuario = User::where("id","=",$request->id)->first();
        try{
            $variable=$request->password;
            $input = $request->validate([
                'password' => 'required|between :5,15',
                'password_confirmation' => 'required|between :5,15|in:'.$variable,
             ]);


            $usuario->password = bcrypt($request->password);
            $usuario->save();
            $user = $usuario;
            return view('auth/login',['msg'=>$msgret] );
        }catch (QueryException $exp ){
            $msgret = ['valor'=>"Erro ao executar a operação",'tipo'=>'danger'];
        }
        return view('auth/passwords/reset',['pageSlug'=>'','token'=>'$token','msg'=>$msgret,'usuario'=>$usuario]);
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

            $msg = ['valor'=>'Usuário/Senha inválido','tipo'=>'danger'];
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

    public function preEdit($id=null){
        return view('profile/edit');
    }

    public function  update(Request $request){
        $msgret = ['valor'=>"Operação realizada com sucesso!",'tipo'=>'success'];

        try{
            $input = $request->validate([
                'name' => 'required|between :5,100',
                'email' => 'required|unique:users,email,'.$request->id,
            ]);

            $usuario = User::find(intval($request->id));
            $usuario->name = $request->name;
            $usuario->email = $request->email;
            $usuario->nomecompleto = $request->nome_completo;
            $usuario->documento = $request->documento;
            $usuario->nacionalidade = $request->nacionalidade;
            $usuario->telefone = $request->telefone;
            $usuario->celular = $request->celular;


            $usuario->save();
        }catch (QueryException $exp ){
            $msgret = ['valor'=>"Erro ao executar a operação",'tipo'=>'danger'];
        }


        return view('profile/edit',['msg'=>$msgret]);
    }
    public function  updateCompletar(Request $request){
        $msgret = ['valor'=>"Operação realizada com sucesso!",'tipo'=>'success'];

        try{

            if ($request->tipopessoa==="F"){
            $input = $request->validate([
                'nome_completo' => 'required|between :20,100',
                'documento' => 'required|cpf|unique:users,documento,'.$request->id,
                'telefone' => 'required',
                'celular' => 'required',
                'email_alternativo' => 'required',
            ]);
            } else{
                $input = $request->validate([
                    'nome_completo' => 'required|between :20,100',
                    'documento' => 'required|cnpj|unique:users,documento,'.$request->id,
                    'telefone' => 'required',
                    'celular' => 'required',
                    'email_alternativo' => 'required',
                ]);

            }

            $usuario = User::find(intval($request->id));
            $usuario->nomecompleto = $request->nome_completo;
            $usuario->documento = $request->documento;
            $usuario->nacionalidade = $request->nacionalidade;
            $usuario->telefone = $request->telefone;
            $usuario->celular = $request->celular;
            $usuario->email_alternativo = $request->email_alternativo;
            $usuario->instagram = $request->instagram;
            $usuario->facebook = $request->facebook;
            $usuario->twitter = $request->twitter;
            $usuario->sexo = $request->sexo;
            $usuario->tipopessoa= $request->tipopessoa;

            $usuario->save();
        }catch (QueryException $exp ){
            $msgret = ['valor'=>"Erro ao executar a operação",'tipo'=>'danger'];
        }


        return view('profile/edit',['msg'=>$msgret]);
    }

    public function delete(Request $request){
        $msgret = ['valor'=>"Operação realizada com sucesso!",'tipo'=>'success'];

        try{

            $usuario = User::find($request->id);
            $usuario->delete();
            $this->logout($request);

        }catch (QueryException $exp ){
            $msgret = ['valor'=>"Erro ao executar a operação",'tipo'=>'danger'];
        }
        return view('auth/login',['msg'=>$msgret] );
     }

     public function addEndereco($id=null){
         $endereco = new Endereco();
        if ($id){
            $endereco = Endereco::find($id);
        }
        return view("profile/endereco",['msg'=>null,'obj'=>$endereco]);
     }

    public function delEndereco($id=null){
        $msgret = ['valor'=>"Operação realizada com sucesso!",'tipo'=>'success'];
        try{
            Endereco::find($id)->delete();
        }
        catch (QueryException $exp ){
            $msgret = ['valor'=>"Erro ao executar a operação",'tipo'=>'danger'];
        }
        $endereco = new Endereco();
        return view("profile/edit",['msg'=>null,'obj'=>$endereco]);
    }

    public function setPrincialEndereco($id=null){
        $msgret = ['valor'=>"Operação realizada com sucesso!",'tipo'=>'success'];
        try{
            $bd = Endereco::find($id);
            DB::table('enderecos')->where('user_id','=',$bd->user_id)->update(['princial'=>false]);
            $bd = Endereco::find($id);
            $bd->princial=True;
            $bd->save();
        }
        catch (QueryException $exp ){
            $msgret = ['valor'=>"Erro ao executar a operação",'tipo'=>'danger'];
        }
        $endereco = new Endereco();
        return view("profile/edit",['msg'=>null,'obj'=>$endereco]);
    }


    public function addEnderecoDo(Request $request){
        $msgret = ['valor'=>"Operação realizada com sucesso!",'tipo'=>'success'];
        try {
            $endereco = new Endereco();
            if ($request->id_add){
                $endereco = Endereco::find($request->id_add);
            }

            $endereco->recebedor = $request->recebedor;
            $endereco->cep = $request->cep;
            $endereco->estado = $request->estado;
            $endereco->cep = $request->cep;
            $endereco->cidade = $request->cidade;
            $endereco->bairro = $request->bairro;
            $endereco->rua = $request->rua;
            $endereco->numero = $request->numero;
            $endereco->complemento = $request->complemento;
            $endereco->informacoes = $request->informacoes;
            if ($request->principal){
            $endereco->princial = $request->principal;
            }else{
                $endereco->princial=false;
            }


            $endereco->user_id= $request->id;
            $endereco->save();

        }catch (QueryException $exception){
            $msgret = ['valor'=>"Erro ao executar a operação",'tipo'=>'danger'];

            dd($exception);
        }

        return view("profile/edit",['msg'=>$msgret,'obj'=>$endereco]);
    }


}
