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
    Route::get('', ['as' => 'instituicoes', 'uses' => 'InstituicaoController@index']);
    Route::get('novo', ['as' => 'instituicoes.novo', 'uses' => 'InstituicaoController@novo']);
    Route::post('salvar', ['as' => 'instituicoes.salvar', 'uses' => 'InstituicaoController@salvar']);
    Route::get('{id}/editar', ['as' => 'instituicoes.editar', 'uses' => 'InstituicaoController@editar']);
    Route::put('{id}/alterar', ['as' => 'instituicoes.alterar', 'uses' => 'InstituicaoController@alterar']);
    Route::get('{id}/excluir', ['as' => 'instituicoes.excluir', 'uses' => 'InstituicaoController@excluir']);
});

Route::group(['prefix' => 'membros', 'where' => ['id' => '[0-9]+']], function () {
    Route::get('', ['as' => 'membros', 'uses' => 'MembroController@index']);
    Route::get('novo', ['as' => 'membros.novo', 'uses' => 'MembroController@novo']);
    Route::post('salvar', ['as' => 'membros.salvar', 'uses' => 'MembroController@salvar']);
    Route::get('{id}/editar', ['as' => 'membros.editar', 'uses' => 'MembroController@editar']);
    Route::put('{id}/alterar', ['as' => 'membros.alterar', 'uses' => 'MembroController@alterar']);
    Route::get('{id}/excluir', ['as' => 'membros.excluir', 'uses' => 'MembroController@excluir']);
});
