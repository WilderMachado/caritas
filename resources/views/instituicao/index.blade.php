@extends('layouts.app')
@section('content')
    <h1>Instituições</h1>
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
                    <button class="btn btn-info btn-buscar" title="Detalhes" value="{{route('instituicoes.detalhar', ['id'=>$instituicao->id])}}">
                        <em class="glyphicon glyphicon-eye-open"></em>
                    </button>
                    <button class="btn btn-primary btn-buscar" title="Membros" value="{{route('instituicoes.membros', ['id'=>$instituicao->id])}}">
                        <em class="glyphicon glyphicon-user"></em>
                    </button>
                    <a class="btn btn-success" title="Editar"
                       href="{{ route('instituicoes.editar', ['id'=>$instituicao->id]) }}">
                        <em class="glyphicon glyphicon-edit"></em>
                    </a>
                    <a class="btn btn-danger btn-excluir" title="Excluir"
                       href="{{ route('instituicoes.excluir', ['id'=>$instituicao->id]) }}">
                        <em class="glyphicon glyphicon-remove"></em>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <a href="{{ route('instituicoes.novo')}}" class="btn btn-primary">Nova Instituição</a>
@endsection