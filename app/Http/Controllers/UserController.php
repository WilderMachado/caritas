<?php

namespace caritas\Http\Controllers;

use caritas\Http\Requests\UserRequest;
use caritas\User;

/**
 * Class UserController classe responsável por interação com opções de Usuários
 * @package caritas\Http\Controllers
 *
 */
class UserController extends Controller
{
    /**Método que realiza carregamento da página inicial de usuários
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::orderBy('name')//Busca usuário, ordena por nome
        ->paginate(config('constantes.paginacao')); //Realiza paginação
        return view('user.index', compact('users'));//Redireciona para página inicial de usuários
    }

    /**Método que redireciona para página de criação de novo usuário
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function novo()
    {
        return view('user.novo');                //Redireciona para página de criação de novo usuárioo
    }

    /**Método que salva novo usuário
     * @param UserRequest $request conjunto de dados para criação de novo usuário
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function salvar(UserRequest $request)
    {
        User::create([                                  //Cria novo usuário
            'name' => $request->name,                   //Passa nome
            'email' => $request->email,                 //Passa e-mail
            'tipo' => $request->tipo,                   //Passa tipo
            'password' => bcrypt($request->password),   //Passa senha após criptografar
        ]);
        return redirect('users');                       //Redireciona para página inicial de usuário
    }
}
