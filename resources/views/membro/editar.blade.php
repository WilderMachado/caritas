@extends('layouts.app')
@section('content')
    <h1 class="text-center">Edição de Membro</h1>
    @include('errors.alert')
    <div class="form-horizontal">
        {!! Form::open(['route'=>['membros.alterar', $membro->id],'method'=>'put']) !!}
        <div class="form-group">
            {!! Form::label ('nome', 'Nome: ',[ 'class'=>'control-label col-xs-2 col-md-3']) !!}
            <div class="col-xs-5 col-md-6">
                {!! Form::text ('nome', $membro->nome, ['class'=>'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label ('instituicao_id', 'Instituição: ',[ 'class'=>'control-label col-xs-2 col-md-3']) !!}
            <div class="col-xs-5 col-md-6">
                {!! Form::select('instituicao_id',$instituicoes, $membro->instituicao_id, ['class'=>'form-control', 'placeholder'=>'']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label ('cargo', 'Cargo: ',[ 'class'=>'control-label col-xs-2 col-md-3']) !!}
            <div class="col-xs-5 col-md-6">
                {!! Form::text ('cargo', $membro->cargo, ['class'=>'form-control']) !!}
            </div>
        </div>
        @php $entidade = $membro; @endphp
        @include('endereco.editar')
        @include('telefone.editar')
        @include('email.editar')
        <div class="form-group">
            <div class="col-xs-offset-2 col-md-offset-3 col-xs-10 col-md-9">
                {!! Form::submit ('Salvar', ['class'=>'btn btn-primary light-blue darken-3']) !!}
            </div>
        </div>
        {!! Form::close()!!}
    </div>
@endsection