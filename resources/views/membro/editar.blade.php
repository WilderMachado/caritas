@extends('layouts.app')
@section('content')
    <div class="panel panel-default panel-table">
        <div class="card-panel  #388e3c green darken-2 center">
            <h1>Edição de Membro</h1>
        </div>
        @include('errors.alert')
        <div class="form-horizontal">
            {!! Form::open(['route'=>['membros.alterar', $membro->id],'method'=>'put']) !!}
            <div class="form-group">
                {!! Form::label ('nome', 'Nome: ',[ 'class'=>'control-label col-xs-2']) !!}
                <div class="col-xs-5">
                    {!! Form::text ('nome', $membro->nome, ['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label ('instituicao_id', 'Instituição: ',[ 'class'=>'control-label col-xs-2']) !!}
                <div class="col-xs-5">
                    {!! Form::select('instituicao_id',$instituicoes, $membro->instituicao_id, ['class'=>'form-control', 'placeholder'=>'']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label ('cargo', 'Cargo: ',[ 'class'=>'control-label col-xs-2']) !!}
                <div class="col-xs-5">
                    {!! Form::text ('cargo', $membro->cargo, ['class'=>'form-control']) !!}
                </div>
            </div>
            @php $entidade = $membro; @endphp
            @include('endereco.editar')
            @include('telefone.editar')
            @include('email.editar')
            <div class="form-group">
                <div class="col-xs-offset-2 col-xs-10">
                    {!! Form::submit ('Salvar', ['class'=>'btn btn-primary light-blue darken-3']) !!}
                </div>
            </div>
            {!! Form::close()!!}
        </div>
    </div>
@endsection