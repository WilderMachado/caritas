@extends('layouts.app')
@section('content')
    <h1>Usuários</h1>
    {{$users->links()}}
    <table class="table">
        <thead>
        <th>Nome</th>
        <th>E-mail</th>
        <th>Tipo</th>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td>{{config('constantes.tipoUsuario')[$user->tipo]}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @can('salvar', 'caritas\User')
    <a href="{{ route('users.novo')}}" class="btn btn-primary">Novo Usuário</a>
    @endcan
@endsection