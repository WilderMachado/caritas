<?php

namespace caritas\Http\Controllers;

use caritas\Endereco;
use caritas\Http\Helpers\EmailHelper;
use caritas\Http\Helpers\TelefoneHelper;
use caritas\Http\Requests\InstituicaoRequest;
use caritas\Instituicao;

/**
 * Class InstituicaoController Classe responsável por interação de opções de Instituições
 * @package caritas\Http\Controllers
 */
class InstituicaoController extends Controller
{
    /**Método que realiza carregamento da página inicial de Instituições
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $instituicoes = Instituicao::orderBy('nome')//Busca instituições e ordena por nome
        ->paginate(config('constantes.paginacao'));                 //Realiza paginação do resultado
        return view('instituicao.index', compact('instituicoes'));  //Redireciona para página inicial de instituições
    }

    /**Método que redireciona para página de criação de nova instituição
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function novo()
    {
        return view('instituicao.novo');    //Redireciona para página de criação nova instituição
    }

    /**Método que salva nova instituição
     * @param InstituicaoRequest $request conjunto de dados para criação de nova instituição
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function salvar(InstituicaoRequest $request)
    {
        $endereco = Endereco::create($request->endereco);           //Cria endereço
        $instituicao = new Instituicao($request->all());            //Instancia nova instituição com dados passados
        $instituicao->endereco()->associate($endereco);             //Associa endereço a instituição
        $instituicao->save();                                       //Salva instituição
        $telefones = TelefoneHelper::filtar($request->telefones);   //Realiza filtragem de telefones recebidos e passa para variável
        if (!empty($telefones)):                                    //Verifica se relação de telefones não está vazia
            $instituicao->telefones()->createMany($telefones);      //Cria telefone
        endif;
        $emails = EmailHelper::filtar($request->emails);            //Realiza filtragem de e-mails recebidos e passa para variável
        if (!empty($emails)):                                       //Verifica se relação de e-mails não está vazia
            $instituicao->emails()->createMany($emails);            //Cria e-mails
        endif;
        return redirect('instituicoes');                            //Redireciona para página inicial de instituições
    }

    /** Método para edição de instituição
     * @param $id int identificador da instituição a ser editada
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editar($id)
    {
        $instituicao = Instituicao::with('endereco', 'telefones', 'emails')->find($id); //Busca instituição
        return view('instituicao.editar', compact('instituicao'));                      //Redireciona para página de edição de instituição
    }

    /**Método que realiza alteração de dados de instituição
     * @param InstituicaoRequest $request conjunto de dados da instituição para alteração
     * @param $id int identificador da instituição
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function alterar(InstituicaoRequest $request, $id)
    {
        $instituicao = Instituicao::with('endereco', 'telefones', 'emails')->find($id); //Busca instituição
        $instituicao->endereco()->update($request->endereco);                           //Altera endereço
        $telefones = TelefoneHelper::filtar($request->telefones);                       //Realiza filtragem de telefones recebidos e passa para variável
        if (count($telefones) == $instituicao->telefones->count()):                     //Verifica se quantidade de telefones é a mesma
            TelefoneHelper::atualizar($instituicao, $telefones);                        //Atualiza dados de telefones por meio do método atualizar da classe TelefoneHelper
        elseif (count($telefones) > $instituicao->telefones->count()):                  //Se quantidade de telefones aumentou
            TelefoneHelper::aumentar($instituicao, $telefones);                         //Aumenta os telefones por meio do método aumentar da classe TelefoneHelper
        else:                                                                           //Caso quantidade de telefones tenha diminuido
            TelefoneHelper::diminuir($instituicao, $telefones);                         //Diminui os telefones com o método diminuir da classe TelefoneHelper
        endif;
        $emails = EmailHelper::filtar($request->emails);                                //Realiza filtragem de e-mails recebidos e passa para variável
        if (count($emails) == $instituicao->emails->count()):                           //Verifica se quantidade de e-mail é a mesma
            EmailHelper::atualizar($instituicao, $emails);                              //Atualiza dados de e-mail por meio do método atualizar da classe EmailHelper
        elseif (count($emails) > $instituicao->emails->count()):                        //Se quantidade de e-mail aumentou
            EmailHelper::aumentar($instituicao, $emails);                               //Aumento os e-mail por meio do método aumentar da classe EmailHelper
        else:                                                                           //Caso quantidade de e-mails tenha diminuido
            EmailHelper::diminuir($instituicao, $emails);                               //Diminui os e-mail com o método diminuir da classe EmailHelper
        endif;
        $instituicao->update($request->all());                                          //Atualiza dados da instituição
        return redirect('instituicoes');                                                //Redireciona para página inicial de instituições
    }

    /**Método que exclui instituição
     * @param int $id identificador da instituição
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function excluir($id)
    {
        $instituicao = Instituicao::find($id);  //Busca instituição por id
        $instituicao->membros()->delete();      //Exclui membros da instituição
        $instituicao->delete();                 //Exclui instituição
        return redirect('instituicoes');        //Redireciona para página inicial de instituições
    }

    /**Método que repassa dados detalhados de instituição
     * @param int $id identificador da instituição
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function detalhar($id)
    {
        //Busca instituição pelo id e repassa para método montarResposta formar o texto e retorna os dados
        return response($this->montarResposta(Instituicao::with('endereco', 'telefones', 'emails')->find($id)));
    }

    /**Método que repassa relação de membros de uma instituição
     * @param int $id identificador da instituição
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function membros($id)
    {
        $instituicao = Instituicao::with('membros')->find($id); //Busca instituição pelo id
        $resposta = "$instituicao->nome\n";                     //Cria variável resposta e inclui nome da instituição
        foreach ($instituicao->membros as $membro):             //Percorre relação de membros da instituição
            $resposta .= "\n$membro->nome";                     //Inclui na resposta nome dos membros da instituição
        endforeach;
        return response($resposta);                             //Retorna resposta
    }

    /**Método privado que monta resposta com dados da instituição em forma de texto
     * @param Instituicao $instituicao entidade cujos dados comporão o texto da resposta
     * @return string texto a ser retornado
     */
    private function montarResposta(Instituicao $instituicao)
    {
        $resposta = "Nome: $instituicao->nome"                              //Cria variável passando nome da instituição
            . "\nEndereço"                                                  //Passa string "Endereço"
            . "\nLogradouro: " . $instituicao->endereco->logradouro         //Passa logradouro
            . "\nNúmero: " . $instituicao->endereco->numero                 //Passa número
            . "\nComplemento: " . $instituicao->endereco->complemento       //Passa complemento
            . "\nBairro: " . $instituicao->endereco->bairro                 //Passa bairro
            . "\nCidade: " . $instituicao->endereco->cidade                 //Passa cidade
            . "-" . $instituicao->endereco->uf;                             //Passa UF
        foreach ($instituicao->telefones as $telefone):                     //Percorre relação de telefones
            $resposta .= "\nTelefone: ($telefone->ddd)$telefone->numero";   //Passa dados do telefone formatado
        endforeach;
        foreach ($instituicao->emails as $email):                           //Percorre relação de e-mails
            $resposta .= "\nE-mail: $email->email";                         //Passa e-mail
        endforeach;
        return $resposta;                                                   //Retorna texto pronto
    }
}