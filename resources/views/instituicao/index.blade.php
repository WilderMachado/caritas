@extends('layouts.app')
@section('content')
    {{$instituicoes->links()}}
    <table class="table">
        <thead>
        <th>Nome</th>
        <th>Telefone</th>
        <th>Email</th>
        <th>Ação</th>
        </thead>
        <tbody>
        @foreach($instituicoes as $instituicao)
            <tr>
                <td>{{$instituicao->nome}}</td>
                <td>
                    @foreach($instituicao->telefones as $telefone)
                        <p>{{"($telefone->ddd) $telefone->numero"}}</p>
                    @endforeach
                </td>
                <td>
                    @foreach($instituicao->emails as $email)
                        <p>{{$email->email}}</p>
                    @endforeach
                </td>
                <td>
                    <a class="btn btn-success" title="Editar"
                       href="{{ route('instituicoes.editar', ['id'=>$instituicao->id]) }}">
                        Editar
                    </a>
                    <a class="btn btn-danger btn-excluir" title="Excluir"
                       href="{{ route('instituicoes.excluir', ['id'=>$instituicao->id]) }}">
                        Excluir
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <a href="{{ route('instituicoes.novo')}}" class="btn btn-primary">Nova Instituição</a>
@endsection