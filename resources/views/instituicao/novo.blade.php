@extends('layouts.app')
@section('content')
    <div class="panel panel-default panel-table">

            <h1>Cadastro de Instituição</h1>

        @include('errors.alert')
        <div class="form-horizontal">
            {!! Form::open(['route'=>'instituicoes.salvar']) !!}

            <div class="form-group">
                {!! Form::label ('nome', 'Nome: ',[ 'class'=>'control-label col-xs-2']) !!}
                <div class="col-xs-5">
                    {!! Form::text ('nome', null, ['class'=>'form-control']) !!}
                </div>
            </div>
            @include('endereco.novo')
            @include('telefone.novo')
            @include('email.novo')
            <div class="form-group">
                <div class="col-xs-offset-2 col-xs-10">
                    {!! Form::submit ('Salvar', ['class'=>'btn btn-primary light-blue darken-3']) !!}
                </div>
            </div>

            {!! Form::close()!!}
        </div>
    </div>
@endsection