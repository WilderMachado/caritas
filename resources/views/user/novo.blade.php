@extends('layouts.app')
@section('content')
    <h1 class="text-center">Cadastro de Usuário</h1>
    @include('errors.alert')
    <div class="form-horizontal">
        {!! Form::open(['route'=>'users.salvar']) !!}
        <div class="form-group">
            {!! Form::label ('name', 'Nome: ',[ 'class'=>'control-label col-xs-2 col-md-3']) !!}
            <div class="col-xs-5 col-md-6">
                {!! Form::text ('name', null, ['class'=>'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label ('email', 'E-mail: ',[ 'class'=>'control-label col-xs-2 col-md-3']) !!}
            <div class="col-xs-5 col-md-6">
                {!! Form::email ('email', null, ['class'=>'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label ('tipo', 'Tipo: ',[ 'class'=>'control-label col-xs-2 col-md-3']) !!}
            <div class="col-xs-5 col-md-6">
                {!! Form::select('tipo',config('constantes.tipoUsuario'), null, ['class'=>'form-control', 'placeholder'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label ('password', 'Senha: ',['class'=>'control-label col-xs-2 col-md-3']) !!}
            <div class="col-xs-5 col-md-6">
                <input type="password" name="password" id="password" class="form-control">
            </div>
        </div>
        <div class="form-group">
            {!! Form::label ('password-confirm', 'Confirmar Senha: ',['class'=>'control-label col-xs-2 col-md-3']) !!}
            <div class="col-xs-5 col-md-6">
                <input type="password" name="password_confirmation" id="password-confirm" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-offset-2 col-md-offset-3 col-xs-10 col-md-9">
                {!! Form::submit ('Salvar', ['class'=>'btn btn-primary light-blue darken-3']) !!}
            </div>
        </div>
        {!! Form::close()!!}
    </div>
@endsection