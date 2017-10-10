<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'instituicoes', 'where' => ['id' => '[0-9]+']], function () {
    Route::get('', ['as' => 'instituicoes', 'uses' => 'InstituicaoController@index'])->middleware('can:visualizar,caritas\Instituicao');
    Route::get('novo', ['as' => 'instituicoes.novo', 'uses' => 'InstituicaoController@novo'])->middleware('can:salvar,caritas\Instituicao');
    Route::post('salvar', ['as' => 'instituicoes.salvar', 'uses' => 'InstituicaoController@salvar'])->middleware('can:salvar,caritas\Instituicao');
    Route::get('{id}/editar', ['as' => 'instituicoes.editar', 'uses' => 'InstituicaoController@editar'])->middleware('can:alterar,caritas\Instituicao');
    Route::put('{id}/alterar', ['as' => 'instituicoes.alterar', 'uses' => 'InstituicaoController@alterar'])->middleware('can:alterar,caritas\Instituicao');
    Route::get('{id}/excluir', ['as' => 'instituicoes.excluir', 'uses' => 'InstituicaoController@excluir'])->middleware('can:excluir,caritas\Instituicao');
    Route::get('{id}/detalhar', ['as' => 'instituicoes.detalhar', 'uses' => 'InstituicaoController@detalhar'])->middleware('can:detalhar,caritas\Instituicao');
    Route::get('{id}/membros', ['as' => 'instituicoes.membros', 'uses' => 'InstituicaoController@membros'])->middleware('can:membros,caritas\Instituicao');
});

Route::group(['prefix' => 'membros', 'where' => ['id' => '[0-9]+']], function () {
    Route::get('', ['as' => 'membros', 'uses' => 'MembroController@index'])->middleware('can:visualizar,caritas\Membro');
    Route::get('novo', ['as' => 'membros.novo', 'uses' => 'MembroController@novo'])->middleware('can:salvar,caritas\Membro');
    Route::post('salvar', ['as' => 'membros.salvar', 'uses' => 'MembroController@salvar'])->middleware('can:salvar,caritas\Membro');
    Route::get('{id}/editar', ['as' => 'membros.editar', 'uses' => 'MembroController@editar'])->middleware('can:alterar,caritas\Membro');
    Route::put('{id}/alterar', ['as' => 'membros.alterar', 'uses' => 'MembroController@alterar'])->middleware('can:alterar,caritas\Membro');
    Route::get('{id}/excluir', ['as' => 'membros.excluir', 'uses' => 'MembroController@excluir'])->middleware('can:excluir,caritas\Membro');
    Route::get('{id}/detalhar', ['as' => 'membros.detalhar', 'uses' => 'MembroController@detalhar'])->middleware('can:detalhar,caritas\Membro');
});

Route::group(['prefix' => 'users', 'where' => ['id' => '[0-9]+']], function () {
    Route::get('', ['as' => 'users', 'uses' => 'UserController@index'])->middleware('can:visualizar,caritas\User');
    Route::get('novo', ['as' => 'users.novo', 'uses' => 'UserController@novo'])->middleware('can:salvar,caritas\User');
    Route::post('salvar', ['as' => 'users.salvar', 'uses' => 'UserController@salvar'])->middleware('can:salvar,caritas\User');
});
