@extends('layouts.app', ['page' => __('Perfil'), 'pageSlug' => 'profile','class'=>'profile'])
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Anúncio') }}</h5>
                    <form autocomplete="off" enctype="multipart/form-data" method="post" action="{{route('advertisement.finalizar', $obj->id)}}" >
                        <input type="hidden" name="id" value="{{$obj->id}}">
                        @csrf

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

                        <div class="card-footer">
                            <a href="{{route('advertisement.back.fotos', $obj->id)}}" class="btn btn-fill btn-primary">{{ __('Voltar') }}</a>
                            <button type="submit" class="btn btn-fill btn-primary">{{ __('Finalizar') }}</button>



                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/functions.js">

    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>


@endsection
