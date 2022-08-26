@extends('layouts.app', ['page' => __('Perfil'), 'pageSlug' => 'profile','class'=>'profile'])
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Anúncio') }}</h5>
                </div>
                <form method="post" action="" autocomplete="off">
                    @csrf
                    <input type="hidden" name="id" value="{{auth()->user()->id}}">
                    <input type="hidden" name="id_add" value="{{$obj->id}}">

                    <div class="card-body">
                        <div class="form-group{{ $errors->has('titulo') ? ' has-danger' : '' }}">
                            <label id="nomecompleto">{{ __('Título') }}</label>
                            <input type="text" name="titulo" class="form-control{{ $errors->has('titulo') ? ' is-invalid' : '' }}" placeholder="{{ __('Título') }}" value="{{ old('titulo', $obj->titulo) }}" required >
                            @include('alerts.feedback', ['field' => 'titulo'])
                        </div>
                        <div class="form-group{{ $errors->has('descricao') ? ' has-danger' : '' }}">
                            <label id="nomecompleto">{{ __('Descrição') }}</label>
                            <input type="text" name="descricao" class="form-control{{ $errors->has('descricao') ? ' is-invalid' : '' }}" placeholder="{{ __('Descrição') }}" value="{{ old('descricao', $obj->descricao) }}" required >
                            @include('alerts.feedback', ['field' => 'descricao'])
                        </div>

                        <div class="row">
                        <div class="form-group{{ $errors->has('preco') ? ' has-danger' : '' }} col-md-6"  >
                            <label >{{ __('Preço') }}</label>
                            <input id="preco" type="text" name="preco" class="form-control{{ $errors->has('preco') ? ' is-invalid' : '' }}" placeholder="{{ __('Preço') }}" value="{{ old('preco', $obj->preco) }}" required >
                            @include('alerts.feedback', ['field' => 'preco'])
                        </div>

                            <div class="form-group{{ $errors->has('qtd') ? ' has-danger' : '' }}  col-md-6 ">
                                <label >{{ __('Quantidade') }}</label>
                                <input id="qtd" type="text" name="qtd" class="form-control{{ $errors->has('qtd') ? ' is-invalid' : '' }}" placeholder="{{ __('Qtd') }}" value="{{ old('preco', $obj->preco) }}" required >
                                @include('alerts.feedback', ['field' => 'qtd'])
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group{{ $errors->has('altura') ? ' has-danger' : '' }} col-md-4"  >
                                <label >{{ __('Altura(cm)') }}</label>
                                <input id="altura" type="text" name="altura" class="form-control{{ $errors->has('altura') ? ' is-invalid' : '' }}" placeholder="{{ __('Altura') }}" value="{{ old('altura', $obj->altura) }}" required >
                                @include('alerts.feedback', ['field' => 'altura'])
                            </div>

                            <div class="form-group{{ $errors->has('largura') ? ' has-danger' : '' }}  col-md-4 ">
                                <label >{{ __('Lagura(cm)') }}</label>
                                <input id="largura" type="text" name="largura" class="form-control{{ $errors->has('largura') ? ' is-invalid' : '' }}" placeholder="{{ __('largura') }}" value="{{ old('largura', $obj->preco) }}" required >
                                @include('alerts.feedback', ['field' => 'largura'])
                            </div>

                            <div class="form-group{{ $errors->has('peso') ? ' has-danger' : '' }}  col-md-4 ">
                                <label >{{ __('Peso Unitário(g)') }}</label>
                                <input id="peso" type="text" name="peso" class="form-control{{ $errors->has('peso') ? ' is-invalid' : '' }}" placeholder="{{ __('peso') }}" value="{{ old('peso', $obj->peso) }}" required >
                                @include('alerts.feedback', ['field' => 'peso'])
                            </div>

                        </div>

                        <div class="form-group{{ $errors->has('foto1') ? ' has-danger' : '' }}">
                                <label >{{ __('Foto 1') }}</label>
                            <input id="foto1" type="text" name="foto1" class="form-control{{ $errors->has('foto1') ? ' is-invalid' : '' }}" placeholder="{{ __('Foto1') }}" value="{{ old('foto1', $obj->foto1)}}" required readonly>
                            @include('alerts.feedback', ['field' => 'foto1'])

                            <input type="file" style="display:none" class="form-control" name="ft1"  size="25" id="arquivo" maxlength="20" accept=".jpg,.png" onchange="showname('arquivo','foto1');"
                            >
                            <div style="margin-top: 10px; margin-bottom: -25px">
                                <input type="button" id="loadFileXml" value="Anexar" onclick="document.getElementById('arquivo').click();" />
                            </div>
                            <br>

                        </div>

                        <div class="form-group{{ $errors->has('foto2') ? ' has-danger' : '' }}">
                            <label >{{ __('Foto 2') }}</label>
                            <input id="foto2" type="text" name="foto2" class="form-control{{ $errors->has('foto2') ? ' is-invalid' : '' }}" placeholder="{{ __('Foto2') }}" value="{{ old('foto2', $obj->foto2) }}" required readonly>
                            @include('alerts.feedback', ['field' => 'foto2'])
                            <input type="file" style="display:none" class="form-control" name="ft1"  size="25" id="foto2i" maxlength="20" accept=".jpg,.png" onchange="showname('foto2i','foto2');"
                            >
                            <div style="margin-top: 10px; margin-bottom: -25px">
                                <input type="button" id="loadFileXml" value="Anexar" onclick="document.getElementById('foto2i').click();" />
                            </div>
                            <br>
                        </div>

                        <div class="form-group{{ $errors->has('foto3') ? ' has-danger' : '' }}">
                            <label >{{ __('Foto 3') }}</label>
                            <input id="foto3" type="text" name="foto3" class="form-control{{ $errors->has('foto3') ? ' is-invalid' : '' }}" placeholder="{{ __('Foto 3') }}" value="{{ old('foto3', $obj->foto3) }}" required readonly>
                            @include('alerts.feedback', ['field' => 'foto3'])

                            <input type="file" style="display:none" class="form-control" name="ft3"  size="25" id="foto3i" maxlength="20" accept=".jpg,.png" onchange="showname('foto3i','foto3');"
                            >
                            <div style="margin-top: 10px; margin-bottom: -25px">
                                <input type="button" id="loadFileXml" value="Anexar" onclick="document.getElementById('foto3i').click();" />
                            </div>
                            <br>

                        </div>

                        <div class="form-group{{ $errors->has('destaque') ? ' has-danger' : '' }}">
                            <label >{{ __('Destaque') }}</label>
                            <input id='foto4' type="text" name="destaque" class="form-control{{ $errors->has('destaque') ? ' is-invalid' : '' }}" placeholder="{{ __('Destaque') }}" value="{{ old('destaque', $obj->destaque) }}"  readonly >
                            @include('alerts.feedback', ['field' => 'complemento'])

                            <input type="file" style="display:none" class="form-control" name="ft4"  size="25" id="foto4i" maxlength="20" accept=".jpg,.png" onchange="showname('foto4i','foto4');"
                            >
                            <div style="margin-top: 10px; margin-bottom: -25px">
                                <input type="button" id="loadFileXml" value="Anexar" onclick="document.getElementById('foto4i').click();" />
                            </div>
                            <br>

                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Salvar') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="/assets/js/functions.js">

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

    <script>
        $(document).ready(function($){
            $("#preco").mask("#.##0,00", {reverse: true});
            $("#qtd").mask("#.##0,00", {reverse: true});
            $("#altura").mask("#.##0,00", {reverse: true});
            $("#largura").mask("#.##0,00", {reverse: true});
            $("#peso").mask("#.##0,00", {reverse: true});
        });

        function showname(id, ret) {
            var name = document.getElementById(id);
            document.getElementById(ret).value =  name.files.item(0).name;
            //alert('Selected file: ' + name.files.item(0).name);
            /*alert('Selected file: ' + name.files.item(0).size);
            alert('Selected file: ' + name.files.item(0).type);*/

        }
    </script>
@endsection
