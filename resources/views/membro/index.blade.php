@extends('layouts.app')
@section('content')
    <h1>Membros</h1>
    {{$membros->links()}}
    <table class="table">
        <thead>
        <th>Nome</th>
        <th>Telefone</th>
        <th>E-mail</th>
        <th>Instituição</th>
        @can('acao','caritas\Membro')
        <th>Ação</th>
        @endcan
        </thead>
        <tbody>
        @foreach($membros as $membro)
            <tr>
                <td>{{$membro->nome}}</td>
                <td>
                    @foreach($membro->telefones as $telefone)
                        <p>{{"($telefone->ddd) $telefone->numero"}}</p>
                    @endforeach
                </td>
                <td>
                    @foreach($membro->emails as $email)
                        <p>{{$email->email}}</p>
                    @endforeach
                </td>
                <td>{{$membro->instituicao->nome}}</td>
                <td>
                    @can('detalhar','caritas\Membro')
                    <button class="btn btn-info btn-buscar" title="Detalhes" value="{{route('membros.detalhar', ['id'=>$membro->id])}}">
                        <em class="glyphicon glyphicon-eye-open"></em>
                    </button>
                    @endcan
                    @can('alterar','caritas\Membro')
                    <a class="btn btn-success" title="Editar"
                       href="{{ route('membros.editar', ['id'=>$membro->id]) }}">
                        <em class="glyphicon glyphicon-edit"></em>
                    </a>
                    @endcan
                    @can('excluir','caritas\Membro')
                    <a class="btn btn-danger btn-excluir" title="Excluir"
                       href="{{ route('membros.excluir', ['id'=>$membro->id]) }}">
                        <em class="glyphicon glyphicon-remove"></em>
                    </a>
                    @endcan
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @can('salvar','caritas\Membro')
    <a href="{{ route('membros.novo')}}" class="btn btn-primary">Novo Membro</a>
    @endcan
@endsection