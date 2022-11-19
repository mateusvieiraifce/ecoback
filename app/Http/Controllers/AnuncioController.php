<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Anuncio;
use App\Models\Comentarios;
use App\Models\CorAnuncio;
use App\Models\Endereco;
use App\Models\Favoritos;
use App\Models\FileAnuncio;
use App\Models\TagsAnuncio;
use App\Models\TipoAnuncio;
use App\Models\User;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class AnuncioController extends Controller
{

    function  list($msg = null)
    {
        $anuncios = Anuncio::where('user_id','=',Auth::user()->id)->orderBy('updated_at','desc')->get();
        return view('advertisement/list', ['anuncios' => $anuncios, 'msg' => $msg]);
    }

    function add()
    {
        return view('advertisement/form', ['obj' => new Anuncio(), 'tipos' => TipoAnuncio::all(), 'cores' => CorAnuncio::all()]);
    }



    function passo1($id){
        $obj = Anuncio::find($id);
        if (!$obj){
            $obj = new Anuncio();
        }
        return view('advertisement/form', ['obj' => $obj, ]);
    }

    function calculaValores($obj){
        $precon = $obj->preco * env('COMISSAO_NORMAL');
        $precod = $obj->preco * env('COMISSAO_DESTAQUE');
        $obj->normal = "R$ ".number_format($precon, 2, ',', '.');
        $obj->destaque = "R$ ".number_format($precod, 2, ',', '.');

    }
    function passo2back($id){
        $obj = Anuncio::find($id);

        if (!$obj){
            $obj = new Anuncio();
        } else{
            $tags = TagsAnuncio::where('adv_id','=',$obj->id)->get();
            $saida = '';
            foreach  ($tags as $tag){
                $saida="#".$tag->descricao." ".$saida;
            }
            $this->calculaValores($obj);

            $obj->hashtag = $saida;

        }
        return view('advertisement/formpreco', ['obj' => $obj,'tipos' => TipoAnuncio::all(), 'cores' => CorAnuncio::all() ]);
    }

    function passo2(Request $request){

        $is_page_refreshed = false;//(isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] == 'max-age=0');

        $input = $request->validate([
            'titulo' => 'required|between :5,100',
            'descricao' => 'required|between :20,300',
            'descricaod' => 'required',

        ]);



      //  $pieces = explode("#", $request->hashtag);

        $anuncio = new Anuncio();


        $anuncio->descricao = $request->descricao;
        $anuncio->titulo = $request->titulo;
        $anuncio->user_id = Auth::user()->id;
        if ($request->id){
            $anuncio = Anuncio::find($request->id);

           // $anuncio->id = $request->id;
        }
        if (!$is_page_refreshed){
            $anuncio->id_anuncio = uniqid(date('HisYmd'));
            $anuncio->descricaod= $request->descricaod;
            $anuncio->material = $request->material;
            DB::connection()->beginTransaction();
            $anuncio->save();
            /*foreach ($pieces as $piece){
                if ($piece!=""){
                    TagsAnuncio::create(['descricao'=>$piece,'adv_id'=>$anuncio->id]);
                }
             }*/
            DB::connection()->commit();
        }
        $this->calculaValores($anuncio);
        $tags = TagsAnuncio::where('adv_id','=',$anuncio->id)->get();
        $saida = '';
        foreach  ($tags as $tag){
            $saida="#".$tag->descricao." ".$saida;
        }
        $anuncio->hashtag = $saida;

        return view('advertisement/formpreco',['id'=>$anuncio->id_anuncio, 'obj'=>$anuncio, 'tipos' => TipoAnuncio::all(), 'cores' => CorAnuncio::all()]);

    }

    function passoFotos($id){
        $anuncio = Anuncio::find($id);

        return view('advertisement/formfotos',['id'=>$anuncio->id_anuncio, 'obj'=>$anuncio]);
    }
    function passo3(Request $request){


        $method = $request->method();

        if ($method == 'GET'){
            dd($request->id);
            $anuncio = Anuncio::find($request->id);
            return view('advertisement/formfotos',['id'=>$anuncio->id_anuncio, 'obj'=>$anuncio]);
        }
        $input = $request->validate([
            'peso' => 'required',
            'altura' => 'required',
            'largura' => 'required',
            'cor' => 'required',
            'tipo' => 'required',
            'hashtag'=>'required'

        ]);

        $id = $request->id;
        if ($id == null){
            return $this->add();
        }


        $pieces = explode("#", $request->hashtag);

        $anuncio = Anuncio::find($request->id);
//        $anuncio->id_anuncio = uniqid(date('HisYmd'));
        DB::connection()->beginTransaction();

        $anuncio->preco = Helper::parseTextDouble($request->preco);
        $anuncio->quantidade = Helper::parseTextDouble($request->qtd);
        $anuncio->type_id = intval($request->tipo);
        $anuncio->altura = Helper::parseTextDouble($request->altura);
        $anuncio->largura = Helper::parseTextDouble($request->largura);
        $anuncio->peso = Helper::parseTextDouble($request->peso);
        $anuncio->color_id = intval($request->cor);
        $anuncio->save();
        TagsAnuncio::where('adv_id','=',$anuncio->id)->delete();
        foreach ($pieces as $piece){
            if ($piece!=""){
                TagsAnuncio::create(['descricao'=>$piece,'adv_id'=>$anuncio->id]);
                /*$existe = TagsAnuncio::where('descricao','=',$piece)->where('adv_id','=',$anuncio->id)->first();
                if (!$existe){

                }*/
            }
        }

        DB::connection()->commit();
        return view('advertisement/formfotos',['id'=>$anuncio->id_anuncio, 'obj'=>$anuncio]);

    }


    function finalizar(Request  $request){


        $msgret = ['valor' => "Operação realizada com sucesso!", 'tipo' => 'success'];
        /*$input = $request->validate([
            'fotoum' => 'required',
            'ft2' => 'required',
            'ft3' => 'required',
       ]);*/

        $validator = Validator::make($request->all(), [
            'fotoum' => 'required',
            'ft2' => 'required',
            'ft3' => 'required',
        ]);


        if ($validator->fails()) {
            return redirect(route('advertisement.passo.fotos',$request->id))
                ->withErrors($validator)
                ->withInput();
        }


        $fileUm = $this->saveFile($request,'fotoum');
        $fileDois = $this->saveFile($request,'ft2');
        $fileTres = $this->saveFile($request,'ft3');
        $fileDestak = $this->saveFile($request,'ft4');

        $pieces = explode("#", $request->hashtag);

        $anuncio = new Anuncio();
        if ($request->id){
            $anuncio= Anuncio::find($request->id);

        }

        $anuncio->destaque =$fileDestak!="";

        try{
            if ($anuncio->destaque){
                FileAnuncio::create(['anuncio_id'=>$anuncio->id,'path'=>$fileDestak]);
            }
        DB::connection()->beginTransaction();
        FileAnuncio::create(['anuncio_id'=>$anuncio->id,'path'=>$fileUm]);
        FileAnuncio::create(['anuncio_id'=>$anuncio->id,'path'=>$fileDois]);
        FileAnuncio::create(['anuncio_id'=>$anuncio->id,'path'=>$fileTres]);
        $anuncio->ativo = '1';
        $anuncio->save();
        DB::connection()->commit();
        }
        catch (QueryException $exception) {
            dd($exception);
            $msgret = ['valor' => "Erro ao executar a operação", 'tipo' => 'danger'];
        }
        return $this->list($msgret);
    }
    function save(Request $request)
    {

        $msgret = ['valor' => "Operação realizada com sucesso!", 'tipo' => 'success'];

        try {

            $input = $request->validate([
                'titulo' => 'required|between :5,100',
                'descricao' => 'required|between :20,300',
                'peso' => 'required',
                'altura' => 'required',
                'largura' => 'required',
                'cor' => 'required',
                'tipo' => 'required',
                'fotoum' => 'required',
                'ft2' => 'required',
                'ft3' => 'required',
                'hashtag'=>'required',

            ]);
            /*subindo arquivos*/
            $fileUm = $this->saveFile($request,'fotoum');
            $fileDois = $this->saveFile($request,'ft2');
            $fileTres = $this->saveFile($request,'ft3');
            $fileDestak = $this->saveFile($request,'ft4');

            $pieces = explode("#", $request->hashtag);

            $anuncio = new Anuncio();
            if ($request->id){
                $anuncio= Anuncio::find($request->id);
            }else{
                $anuncio->id_anuncio = uniqid(date('HisYmd'));
            }

            DB::connection()->beginTransaction();
            $anuncio->descricao = $request->descricao;
            $anuncio->titulo = $request->titulo;
            $anuncio->preco = Helper::parseTextDouble($request->preco);
            $anuncio->quantidade = Helper::parseTextDouble($request->qtd);
            $anuncio->destaque =$fileDestak!="";

            $anuncio->user_id = Auth::user()->id;
            $anuncio->type_id = intval($request->tipo);

            $anuncio->altura = Helper::parseTextDouble($request->altura);
            $anuncio->largura = Helper::parseTextDouble($request->largura);
            $anuncio->peso = Helper::parseTextDouble($request->peso);
            $anuncio->color_id = intval($request->cor);

            $anuncio->save();
            FileAnuncio::create(['anuncio_id'=>$anuncio->id,'path'=>$fileUm]);
            FileAnuncio::create(['anuncio_id'=>$anuncio->id,'path'=>$fileDois]);
            FileAnuncio::create(['anuncio_id'=>$anuncio->id,'path'=>$fileTres]);
            if ($anuncio->destaque){
                FileAnuncio::create(['anuncio_id'=>$anuncio->id,'path'=>$fileDestak]);
            }
            foreach ($pieces as $piece){
                if ($piece!=""){
                    TagsAnuncio::create(['descricao'=>$piece,'adv_id'=>$anuncio->id]);
                }
            }

            DB::connection()->commit();

        } catch (QueryException $exception) {
            $msgret = ['valor' => "Erro ao executar a operação", 'tipo' => 'danger'];

          //  dd($exception);
        }
        return $this->list($msgret);

    }

    private function saveFile(Request $request, $namefileR){

        if ($request->hasFile($namefileR) && $request->file($namefileR)) {
            $name = uniqid(date('HisYmd'));

            // Recupera a extensão do arquivo
            $extension = $request->$namefileR->extension();

            // Define finalmente o nome
            $nameFile = "{$name}.{$extension}";
            $file = $nameFile;
            // Faz o upload:
            $upload = $request->$namefileR->storeAs('products', $nameFile);
            return $nameFile;
        } else{
            return "";
        }
    }

    public function delete($id=null){
        $msgret = ['valor'=>"Operação realizada com sucesso!",'tipo'=>'success'];
        try{
            $x =  Anuncio::find($id);
            $x->ativo = 0;
            if ($x){
                $x->save();
            }
        }
        catch (QueryException $exp ){
            $msgret = ['valor'=>"Erro ao executar a operação",'tipo'=>'danger'];
        }
        $endereco = new Endereco();
        return $this->list($msgret);
    }

    public function edit($id=null){

        $x =  new Anuncio();
        try{
            $x =  Anuncio::find($id);

            $tags = TagsAnuncio::where('adv_id','=',$id)->get();
            $saida = "";
            foreach ($tags as $tag){
                $saida=$saida."#".$tag->descrica;
            }

            $files = FileAnuncio::where('anuncio_id','=',$id)->get();
            if (sizeof($files)>0 && $files[0]){
                $x->foto1= $files[0]->path;
            }
            if (sizeof($files)>1 && $files[1]){
                $x->foto2= $files[1]->path;
            }
            if (sizeof($files)>2 && $files[2]){

                $x->foto3= $files[2]->path;
            }

            if (sizeof($files)>3 && $files[3]){
                $x->destaque = $files[3];
            }

            $x->hashtag =$saida;

        }
        catch (QueryException $exp ){
            $msgret = ['valor'=>"Erro ao executar a operação",'tipo'=>'danger'];
        }
        $endereco = new Endereco();
        return view('advertisement/form', ['obj' =>$x, 'tipos' => TipoAnuncio::all(), 'cores' => CorAnuncio::all()]);
    }

    public function produtctDetail($id=null, $msg=null){

        $x =  new Anuncio();
        $comentarios = null;
        $msgret = $msg;
        try{

            $x =  Anuncio::join('type_adv','type_adv.id','=','type_id')->
            join('users','users.id','=','user_id')->where('anuncios.id','=',$id)->orWhere('anuncios.id_anuncio','=',$id)->
                select(DB::raw('anuncios.*'))->first();

            $id = $x->id;

            $comentarios = Comentarios::where('anuncio_id','=',$id)->orderBy('created_at','desc')->get();
            $tags = TagsAnuncio::where('adv_id','=',$id)->get();

            $saida = "";
            foreach ($tags as $tag){
                $saida=$saida."#".$tag->descrica;
            }

            $files = FileAnuncio::where('anuncio_id','=',$id)->get();
            if (sizeof($files)>0 && $files[0]){
                $x->foto1= $files[0]->path;
            }
            if (sizeof($files)>1 && $files[1]){
                $x->foto2= $files[1]->path;
            }
            if (sizeof($files)>2 && $files[2]){

                $x->foto3= $files[2]->path;
            }

            if (sizeof($files)>3 && $files[3]){
                $x->destaque = $files[3]->path;
            }

            $x->hashtag =$saida;

        }
        catch (QueryException $exp ){
            $msgret = ['valor'=>"Erro ao executar a operação",'tipo'=>'danger'];
        }

        return view('frente/produto', ['obj' =>$x, 'tipos' => TipoAnuncio::all(), 'cores' => CorAnuncio::all(), 'comentarios'=>$comentarios,'msg'=>$msg]);
    }

    public function addSession(Request $request){

        $obj = ['id'=>$_GET['produto'], 'qtd'=>$_GET['qtd']];

        if ($request->session()->has('produtos')) {

            $produtos = session('produtos');

        }else{

            $produtos = array();

        }
        array_push($produtos,$obj);
        session(['produtos' => $produtos]);
        return "ok";
    }

    public function addFavorite(Request $request){

        $path = $request->getPathInfo();
        $result = explode('/',$path);
        $obj = $result[sizeof($result)-1];
        if ($request->session()->has('favoritos')) {
            $fav = session('favoritos');
        }else{
            $fav = array();
        }

        if (Auth::check()){

            $favBD = Favoritos::where('anuncio_id','=',@$obj)
                ->where('comprador_id','=',Auth::user()->id)->first();
            if ($favBD==null){
            $favBD = new Favoritos();
            }
            $favBD->anuncio_id = $obj;
            $favBD->comprador_id = Auth::user()->id;
            $favBD->save();
        }
        array_push($fav,$obj);
        session(['favoritos' => $fav]);
        return back();
    }

    public function remFavorite($id){

        if (session()->has('favoritos')) {
            $fav = session('favoritos');
            $new = array_diff($fav,[$id]);
            session(['favoritos' => $new]);
            //dd($new);
        }

        if (Auth::check()){
            $favor = Favoritos::where('comprador_id','=',Auth::id())
                ->where('anuncio_id','=',$id)->first();
            if ($favor){
                $favor->delete();
            }
        }

        return back();
    }

    public function listFavorite(){
        if (session()->has('favoritos')) {
            $fav = session('favoritos');
            $lista  = Anuncio::whereIn('id',$fav)->get();
        } else{
            $lista = [];
        }

        return view('frente.favoritos',['anuncios'=>$lista]);
    }

    public function viewSession(){
        dd(session('produtos'));
    }

    public function clearCarr(){
        session(['produtos' => array()]);
        return back();
    }

    public function  addComentario(Request $request){
        $msgret = ['valor' => "Operação realizada com sucesso!", 'tipo' => 'success'];
        try{
            $comentario = new Comentarios();
            $comentario->anuncio_id = $request->anuncio_id;
            $comentario->nome = $request->name;
            $comentario->email = $request->email;
            $usuario = User::where('email','=',$request->email)->first();
            if (isset($usuario)){
                $comentario->comprador_id = $usuario->id;
            }
            $comentario->pontos = $request->rating;
            $comentario->descricao = $request->review;
            $comentario->save();

        }catch (QueryException $exception) {
            $msgret = ['valor' => "Erro ao executar a operação", 'tipo' => 'danger'];
        }
        session(['msg' => $msgret]);
        return back()->withInput();
    }



    //
}
