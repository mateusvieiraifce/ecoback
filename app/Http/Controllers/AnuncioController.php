<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Anuncio;
use App\Models\CorAnuncio;
use App\Models\Endereco;
use App\Models\FileAnuncio;
use App\Models\TagsAnuncio;
use App\Models\TipoAnuncio;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnuncioController extends Controller
{

    function  list($msg = null)
    {
        $anuncios = Anuncio::all();
        return view('advertisement/list', ['anuncios' => $anuncios, 'msg' => $msg]);
    }

    function add()
    {
        return view('advertisement/form', ['obj' => new Anuncio(), 'tipos' => TipoAnuncio::all(), 'cores' => CorAnuncio::all()]);
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

            dd($exception);
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
            if ($x){
                $x->delete();
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


    //
}
