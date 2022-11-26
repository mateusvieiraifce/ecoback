<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use App\Models\ItensVenda;
use App\Models\Vendas;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendasController extends Controller
{

    function list($msg=null){

        $lista = ItensVenda::join('anuncios','anuncios.id','=','itens_vendas.anuncio_id')
            ->join('tamanhos','tamanhos.id','=','itens_vendas.tamanho')
            ->join('vendas','vendas.id','=','itens_vendas.venda_id')->where('vendedor_id','=',Auth::user()->id)
            ->select(['destaque','itens_vendas.id', 'titulo','itens_vendas.quantidade','preco_item','tamanhos.descricao','data_pago','data_envio'])->get();

        return view("sales/lista",['anuncios'=>$lista,'msg'=>$msg]);

    }

    function send($id){

        $anuncio = ItensVenda::join('anuncios','anuncios.id','=','itens_vendas.anuncio_id')
            ->join('tamanhos','tamanhos.id','=','itens_vendas.tamanho')
            ->join('vendas','vendas.id','=','itens_vendas.venda_id')
            ->join('enderecos','enderecos.id','=','vendas.endereco_id')
            ->join('users','users.id','=','anuncios.user_id')->where('vendedor_id','=',Auth::user()->id)->where('itens_vendas.id','=',$id)
            ->select(['destaque','itens_vendas.id as id', 'titulo',
                'itens_vendas.quantidade','preco_item'
                ,'tamanhos.descricao','data_pago','enderecos.rua','enderecos.bairro','enderecos.cidade','enderecos.cep', 'users.name','data_envio'])->first();
     //   dd($anuncio);

        return view('sales/form',['obj'=>$anuncio,'id'=>$id]);

    }
    function sendDo($id)
    {
        $msgret = ['valor'=>"Operação realizada com sucesso!",'tipo'=>'success'];
        try{
        $anu = ItensVenda::find($id);
        $anu->data_envio = $date = date('Y-m-d H:i:s');
        $anu->save();
        }catch (QueryException $exception){
            $msgret = ['valor'=>"Erro ao executar a operação",'tipo'=>'danger'];
        }

        return $this->list($msgret);

    }

        //
}
