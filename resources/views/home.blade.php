@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Bem vindo(a)!</div>

                    <div class="panel-body">
                        <p>
                            <strong>{{Auth::user()->name}}</strong>
                        </p>
                        <p>No Sistema de Cadastro da <strong><em>Cáritas</em> Diocesana de Eunápolis</strong> você pode:
                        </p>
                        <ul>
                            @can('salvar','caritas\Instituicao')
                            <li>Cadastrar dados de Instituições</li>
                            @endcan
                            @can('visualizar','caritas\Instituicao')
                            <li>Consultar relação de Instituições</li>
                            @endcan
                            @can('detalhar','caritas\Instituicao')
                            <li>Consultar dados de Instituições</li>
                            @endcan
                            @can('membros','caritas\Instituicao')
                            <li>Visualizar relação de membros de Instituições</li>
                            @endcan
                            @can('alterar','caritas\Instituicao')
                            <li>Alterar dados de Instituições</li>
                            @endcan
                            @can('excluir','caritas\Instituicao')
                            <li>Excluir cadastro de instituições</li>
                            @endcan

                            @can('salvar','caritas\Membro')
                            <li>Cadastrar dados de Membros de instituições</li>
                            @endcan
                            <li>Consultar relação de Membros de instituições</li>
                            @can('detalhar','caritas\Membro')
                            <li>Consultar dados de Membros de instituições</li>
                            @endcan
                            @can('alterar','caritas\Membro')
                            <li>Alterar dados de Membros de instituições</li>
                            @endcan
                            @can('alterar','caritas\Membro')
                            <li>Excluir cadastro de Membros de instituições</li>
                            @endcan

                            @can('salvar','caritas\User')
                            <li>Cadastrar Usuários do sistema</li>
                            @endcan
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
