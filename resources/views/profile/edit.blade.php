@extends('layouts.app', ['page' => __('Perfil'), 'pageSlug' => 'profile','class'=>'profile'])

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Dados da Conta') }}</h5>
                </div>
                <form method="post" action="{{route('user.update')}}" autocomplete="off">
                    <div class="card-body">
                            @csrf
                            @include('alerts.success')

                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label>{{ __('Name') }}</label>
                                <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', auth()->user()->name) }}">
                                @include('alerts.feedback', ['field' => 'name'])
                            </div>

                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                <label>{{ __('Email address') }}</label>
                                <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email address') }}" value="{{ old('email', auth()->user()->email) }}">
                                @include('alerts.feedback', ['field' => 'email'])
                                <input type="hidden" name="id" value="{{auth()->user()->id}}">
                            </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Dados Pessoais') }}</h5>
                </div>
                <form method="post" action="#" autocomplete="off">
                    <div class="card-body">
                        @csrf
                        @method('put')

                        @include('alerts.success', ['key' => 'password_status'])

                        <div class="form-group{{ $errors->has('old_password') ? ' has-danger' : '' }}">
                            <label>{{ __('Nome Completo') }}</label>
                            <input type="text" name="nome_completo" class="form-control{{ $errors->has('old_password') ? ' is-invalid' : '' }}" placeholder="{{ __('Nome Completo') }}" value="" required>
                            @include('alerts.feedback', ['field' => 'old_password'])
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <label>{{ __('CPF/CNPJ') }}</label>
                            <input type="text" name="documento" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Cpf/CNPJ') }}" value="" required>
                            @include('alerts.feedback', ['field' => 'password'])
                        </div>
                        <div class="form-group">
                            <label>{{ __('Nacionalidade') }}</label>
                            <input type="text" name="nacionalidade" class="form-control" placeholder="{{ __('Nacionalidade') }}" value="" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Telefone') }}</label>
                            <input type="text" name="telefone" class="form-control" placeholder="{{ __('Telefone') }}" value="" required>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Celular') }}</label>
                            <input type="text" name="celular" class="form-control" placeholder="{{ __('Celular') }}" value="" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Change password') }}</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card card-tasks">
                            <div class="card-header ">
                                <h6 class="title d-inline">Endereços</h6>

                            </div>
                            <div class="card-body ">
                                <div class="table-full-width table-responsive">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="checkbox" value="">
                                                        <span class="form-check-sign">
                                                    <span class="check"></span>
                                                </span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="title">Update the Documentation</p>
                                                <p class="text-muted">Dwuamish Head, Seattle, WA 8:47 AM</p>
                                            </td>
                                            <td class="td-actions text-right">
                                                <button type="button" rel="tooltip" title="" class="btn btn-link" data-original-title="Edit Task">
                                                    <i class="tim-icons icon-pencil"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="card card-user">
                <div class="card-body">
                    <p class="card-text">
                        <div class="author">
                            <div class="block block-one"></div>
                            <div class="block block-two"></div>
                            <div class="block block-three"></div>
                            <div class="block block-four"></div>
                            <a href="#">
                                <img class="avatar" src="{{ auth()->user()->avatar }}" alt="">
                                <h5 class="title">{{ auth()->user()->name }}</h5>
                            </a>

                        </div>
                    </p>

                </div>
                <div class="card-footer">
                    <div class="button-container">
                        <button class="btn btn-icon btn-round btn-facebook">
                            <i class="fab fa-facebook"></i>
                        </button>
                        <button class="btn btn-icon btn-round btn-twitter">
                            <i class="fab fa-twitter"></i>
                        </button>
                        <button class="btn btn-icon btn-round btn-google">
                            <i class="fab fa-google-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
