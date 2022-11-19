@extends('layouts.app', ['page' => __('Anúncios'), 'pageSlug' => 'advertisement','class'=>'advertisement'])
@section('content')
    <div class="card">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card card-tasks">
                    <div class="card-header ">
                        <h6 class="title d-inline">Anúncios</h6>
                        <div class="dropdown">
                            <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown">
                                <i class="tim-icons icon-settings-gear-63"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="{{route('advertisement.add')}}">Adicionar</a>
                            </div>
                        </div>

                    </div>
                    <div class="card-body ">
                        <div class="table-full-width table-responsive">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td>Título</td>
                                    <td>Qtd</td>
                                    <td>Preço (R$) </td>
                                    <td>Editar</td>
                                    <td>Desativar</td>
                                    <td>Destacar</td>
                                </tr>

                                @foreach($anuncios as $ende)
                                    <tr style="height: 20px">

                                        <td style="max-width: 200px;">
                                            <p class="title">{{$ende->titulo}}</p>
                                        </td>

                                        <td>
                                            <p class="title">{{\App\Helper::padronizaMonetario($ende->quantidade)}}</p>
                                        </td>

                                        <td>
                                            <p class="title">{{\App\Helper::padronizaMonetario($ende->preco)}}</p>
                                        </td>

                                        <td class="td-actions text-left">
                                            <a href="{{route('advertisement.edit',$ende->id)}}">
                                                <button type="button" rel="tooltip" title="" class="btn btn-link" data-original-title="Edit Task">
                                                    <i class="tim-icons icon-pencil"></i>
                                                </button>
                                            </a>
                                        </td>
                                        <td class="td-actions text-left">
                                            <a onclick="return confirm('Deseja realmente desativar?') " href="{{route('advertisement.delete',$ende->id)}}">
                                                <button type="button" rel="tooltip" title="" class="btn btn-link" data-original-title="Edit Task">
                                                    <i class="tim-icons icon-simple-remove"></i>
                                                </button>
                                            </a>
                                        </td>

                                        <td class="td-actions text-left">
                                            <a href="">
                                                <button type="button" rel="tooltip" title="" class="btn btn-link" data-original-title="Edit Task">
                                                    <i class="tim-icons icon-heart-2"></i>
                                                </button>
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
