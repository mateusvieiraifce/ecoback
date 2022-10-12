<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutControler extends Controller
{
    function checkout(){
        if (Auth::check()){
        return view('frente/shoping');
        } else{
            session(['nextview' => 'frente/shoping']);
            return view("auth/login");
        }
    }

    public function removerQntCarrinho($id){

        if (session()->has('produtos')) {

            $produtos = session('produtos');
            $saida = array();
            foreach ($produtos as $pd){
                if ($pd['id']==$id){
                    if ($pd['qtd']>0){
                        $pd['qtd']=$pd['qtd']-1;
                    }else{
                        $pd['qtd']= 0;
                    }
                }
                if ($pd['qtd']>0){
                    array_push($saida,$pd);
                }
            }

        }

        session(['produtos' => $saida]);
        return back();
    }

    public function addQntCarrinho($id){

        if (session()->has('produtos')) {

            $produtos = session('produtos');
            $saida = array();
            foreach ($produtos as $pd){
                if ($pd['id']==$id){
                    if ($pd['qtd']>0){
                        $pd['qtd']=$pd['qtd']+1;
                    }else{
                        $pd['qtd']= 0;
                    }
                }
                if ($pd['qtd']>0){
                    array_push($saida,$pd);
                }
            }

        }

        session(['produtos' => $saida]);
        return back();
    }

    //
}
