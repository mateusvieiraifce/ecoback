<?php

namespace App\Http\Controllers;

use App\Helpers\PagSeguro;
use App\Models\Anuncio;
use App\Models\Endereco;
use App\Models\ItensVenda;
use App\Models\User;
use App\Models\Vendas;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutControler extends Controller
{
    function checkout()
    {

        if (Auth::check()) {
            return view('frente/shoping');
        } else {

            session(['nextview' => '/checkout']);
            return view("auth/login");
        }
    }

    public function removerQntCarrinho($id)
    {

        if (session()->has('produtos')) {

            $produtos = session('produtos');
            $saida = array();
            foreach ($produtos as $pd) {
                if ($pd['id'] == $id) {
                    if ($pd['qtd'] > 0) {
                        $pd['qtd'] = $pd['qtd'] - 1;
                    } else {
                        $pd['qtd'] = 0;
                    }
                }
                if ($pd['qtd'] > 0) {
                    array_push($saida, $pd);
                }
            }

        }

        session(['produtos' => $saida]);
        return back();
    }

    public function addQntCarrinho($id)
    {

        if (session()->has('produtos')) {

            $produtos = session('produtos');
            $saida = array();
            foreach ($produtos as $pd) {
                if ($pd['id'] == $id) {
                    if ($pd['qtd'] > 0) {
                        $pd['qtd'] = $pd['qtd'] + 1;
                    } else {
                        $pd['qtd'] = 0;
                    }
                }
                if ($pd['qtd'] > 0) {
                    array_push($saida, $pd);
                }
            }

        }

        session(['produtos' => $saida]);
        return back();
    }

    public function create(Request $request)
    {
        $msg = "Não há produtos na carrinho de compras para finalizar o pedido!";
        //TODO VALIDAR

        try {
            if (session()->has('produtos')) {
                $produtos = session('produtos');
                $saida = array();
                $total = 0;
                foreach ($produtos as $pd) {
                    $anc = Anuncio::find($pd['id']);
                    $item = new ItensVenda();
                    $item->quantidade = $pd['qtd'];
                    $item->preco_item = $anc->preco;
                    $item->anuncio_id = $anc->id;
                    $item->vendedor_id = $anc->user_id;
                    $total = $total + ($item->quantidade * $item->preco_item);
                    array_push($saida, $item);
                }
                DB::connection()->beginTransaction();
                $venda = new Vendas();
                $venda->id_venda = uniqid(date('HisYmd'));
                $frete = $request->frete;
                $venda->total = $total + $frete;
                $venda->valor = $total;
                $venda->comprador_id = Auth::user()->id;
                $venda->endereco_id = $request->enderecos;
                $venda->save();
                foreach ($saida as $iten) {
                    $iten->venda_id = $venda->id;
                    $iten->save();
                }
                DB::connection()->commit();
                $msg = 'Sua compra está sendo processada, em breve você receberá um email com a confirmação dos seus dados! EcoModa Agradece a preferência';
                $this->clearCarr();
                return $this->processaPagSeguro($venda);
                //
            }


            /*$link_de_pagamento = $PagSeguro->getPaymentLinkByTransactionCode($venda_realizada['transaction_code']);*/


        } catch (QueryException $exception) {
            $msgret = ['valor' => "Erro ao executar a operação", 'tipo' => 'danger'];
            dd($exception);
        }

        return view('frente.msg', ['msg_compra' => $msg]);

    }
    public function clearCarr(){
        session(['produtos' => array()]);
        return back();
    }

    public function posProcessPagamento($id){
        $PagSeguro = new PagSeguro();
        $response = $PagSeguro->getByReference("venda_{$id}");
        $venda = Vendas::find($id);
        if (isset($response->status)) {
            if ($response->status != $venda->status_pagseguro) {
                $texto_status = $PagSeguro->getStatusText($response->status);
                $texto_metodo = $PagSeguro->getPaymentMethodText($response->paymentMethod->type);
                $taxa = $response->feeAmount;
                $valor_liquido = $response->netAmount;

                if (isset($venda->data_pagamento) && !is_null($venda->data_pagamento)) {
                    $data_pagamento = $venda->data_pagamento;
                    $pagamento_identificado = true;
                } elseif ($response->status == 3 || $response->status == 4) {
                    $data_pagamento = date('Y-m-d');
                    $pagamento_identificado = true;
                }

                $venda->transaction_pag_seguro=$response->code;
                $venda->status_pagseguro=$response->status;
                $venda->txt_status_pagseguro=$texto_status;
                $venda->status_metodo=$response->paymentMethod->type;
                $venda->txt_status_metodo=$texto_metodo;
                $venda->taxa_operadora = $taxa;
                $venda->valor_liquido=$valor_liquido;
                $venda->data_pagamento= $data_pagamento;
                $venda->save();
                //$venda->setStatusPagSeguro($response->code, $response->status, $id_venda, $texto_status, $response->paymentMethod->type, $texto_metodo, $data_pagamento, $taxa, $valor_liquido);
                //$venda_row = $venda->getByChave($id_venda);
            }
        }


        $msg = 'Seu pagamento foi confirmado, em breve lhe enviaremos seu produto! EcoModa Agradece a preferência';
        return view('frente.msg', ['msg_compra' => $msg]);
    }
    public function processaPagSeguro($venda_realizada){
        $PagSeguro = new PagSeguro();
        $descricao_venda_pagseguro = "ITEMS COMPRADOS NA ECOMODA";
         //= Vendas::find($id);
        $pessoa = User::find($venda_realizada->comprador_id);
        $endereco = Endereco::find($venda_realizada->endereco_id);

        $dados_venda = array(
            'codigo' => "venda_{$venda_realizada->id}",
            'valor' => $venda_realizada->total,
            'quantidade' => $venda_realizada->quantidade,
            'descricao' => $descricao_venda_pagseguro,
            'nome' => $pessoa->nome,
            'email' => $pessoa->email,
            'telefone' => "88997141874",
            'codigo_pagseguro' => $venda_realizada->id_venda,
            'transaction_id' => '',
            'cep'=>$endereco->cep,
        );

        $url_retorno = route('vendas.payment',$venda_realizada->id);;//env('local')"/pagamento_sucesso.php?venda={$venda_realizada->id}";
      //  dd($url_retorno);
        $link_de_pagamento = $PagSeguro->generatePaymentLink($dados_venda, $url_retorno);
        return redirect($link_de_pagamento);
   }

    //
}
