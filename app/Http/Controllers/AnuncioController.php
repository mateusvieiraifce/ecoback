<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use Illuminate\Http\Request;

class AnuncioController extends Controller
{

    function  list(){
        $anuncios = Anuncio::all();
        return view('advertisement/list',['anuncios'=>$anuncios]);
    }

    function add(){
        return view('advertisement/form',['obj'=>new Anuncio()]);
    }
    //
}
