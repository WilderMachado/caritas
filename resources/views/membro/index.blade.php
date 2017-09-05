@extends('layouts.app')
@section('content')
    {{$membros->links()}}
    <table class="table">
        <thead>
        <th>Nome</th>
        <th>Telefone</th>
        <th>Email</th>
        <th>Instituição</th>
        <th>Ação</th>
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
                    <button class="btn btn-primary btn-buscar" title="Detalhes" value="{{route('membros.detalhar', ['id'=>$membro->id])}}">
                        Detalhes
                    </button>
                    <a class="btn btn-success" title="Editar"
                       href="{{ route('membros.editar', ['id'=>$membro->id]) }}">
                        Editar
                    </a>
                    <a class="btn btn-danger btn-excluir" title="Excluir"
                       href="{{ route('membros.excluir', ['id'=>$membro->id]) }}">
                        Excluir
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <a href="{{ route('membros.novo')}}" class="btn btn-primary">Novo Membro</a>
@endsection