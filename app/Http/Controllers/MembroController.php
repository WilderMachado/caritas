<?php

namespace caritas\Http\Controllers;

use caritas\Endereco;
use caritas\Http\Helpers\EmailHelper;
use caritas\Http\Helpers\TelefoneHelper;
use caritas\Http\Requests\MembroRequest;
use caritas\Instituicao;
use caritas\Membro;

/**
 * Class MembroController classe responsável por interação com opções de Membros
 * @package caritas\Http\Controllers
 */
class MembroController extends Controller
{
    /**Método que realiza carregamento da página inicial de Membros
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $membros = Membro::with('instituicao', 'telefones', 'emails')//Busca membros
        ->orderBy('nome')->paginate(config('constantes.paginacao'));    //Ordena por nome e realiza paginação
        return view('membro.index', compact('membros'));                //Redireciona para página inicial de membros
    }

    /**Método que redireciona para página de criação de novo membro
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function novo()
    {
        $instituicoes = Instituicao::orderBy('nome')->pluck('nome', 'id');  //Busca instituições ordenando por nome e pega nome e id
        return view('membro.novo', compact('instituicoes'));                //Redireciona para página de criação de novo membro
    }

    /**Método que salva novo membro
     * @param MembroRequest $request conjunto de dados para criação de novo membro
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function salvar(MembroRequest $request)
    {
        $endereco = Endereco::create($request->endereco);           //Cria endereço
        $membro = new Membro($request->all());                      //Instancia novo membro com dados passados
        $membro->endereco()->associate($endereco);                  //Associa endereço a membro
        $membro->save();                                            //Salva membro
        $telefones = TelefoneHelper::filtar($request->telefones);   //Realiza filtragem de telefones recebidos e passa para variável
        if (!empty($telefones)):                                    //Verifica se relação de telefones não está vazia
            $membro->telefones()->createMany($telefones);           //Cria telefones
        endif;
        $emails = EmailHelper::filtar($request->emails);            //Realiza filtragem de e-mails recebidos e passa para variável
        if (!empty($emails)):                                       //Verifica se relação de e-mails não está vazia
            $membro->emails()->createMany($emails);                 //Cria e-mails
        endif;
        return redirect('membros');                                 //Redireciona para página inicial de membros
    }

    /**Método para edição de membro
     * @param int $id identificador do membro a ser editado
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editar($id)
    {
        $membro = Membro::with('instituicao', 'endereco', 'telefones', 'emails')->find($id);//Busca membro
        $instituicoes = Instituicao::orderBy('nome')->pluck('nome', 'id');                  //Busca instituicoes, ordena por nome e pega nome e id
        return view('membro.editar', compact('membro', 'instituicoes'));                    //Redireciona para página de edição de membro
    }

    /**Método que realiza alteração de dados de meembro
     * @param MembroRequest $request conjunto de dados do membro para alteração
     * @param int $id identificador do membro
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function alterar(MembroRequest $request, $id)
    {
        $membro = Membro::with('instituicao', 'endereco', 'telefones', 'emails')->find($id);//Busca membro
        $membro->endereco->update($request->endereco);                                      //Altera endereço
        $telefones = TelefoneHelper::filtar($request->telefones);                           //Realiza filtragem de telefones recebidos e passa para variável
        if (count($telefones) == $membro->telefones->count()):                              //Verifica se quantidade de telefones é a mesma
            TelefoneHelper::atualizar($membro, $telefones);                                 //Atualiza dados de telefones por meio do método atualizar da classe TelefoneHelper
        elseif (count($telefones) > $membro->telefones->count()):                           //Se quantidade de telefone aumentou
            TelefoneHelper::aumentar($membro, $telefones);                                  //Aumenta os telefones por meio do método aumentar da classe TelefoneHelper
        else:                                                                               //Caso quantidade de telefones tenha diminuido
            TelefoneHelper::diminuir($membro, $telefones);                                  //Diminui os telefones com o método diminuir da classe TelefoneHelper
        endif;
        $emails = EmailHelper::filtar($request->emails);                                    //Realiza filtragem de e-mails recebidos e passa para variável
        if (count($emails) == $membro->emails->count()):                                    //Verifica se quantidade de e-mail é a mesma
            EmailHelper::atualizar($membro, $emails);                                       //Atualiza dados de e-mails por meio do método atualizar da classe EmailHelper
        elseif (count($emails) > $membro->emails->count()):                                 //Se quantidade de e-mail aumentou
            EmailHelper::aumentar($membro, $emails);                                        //Aumenta os e-mails por meio do método aumentar da classe EmailHelper
        else:                                                                               //Caso quantidade de e-mail tenha diminuido
            EmailHelper::diminuir($membro, $emails);                                        //Diminui os e-mails com o método diminuir da classe EmailHelper
        endif;
        $membro->update($request->all());                                                   //Atualiza dados do membro
        return redirect('membros');                                                         //Redireciona para página inicial de membros
    }

    /**Método que exclui membro
     * @param int $id identificador do membro
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function excluir($id)
    {
        Membro::destroy($id);       //Exclui membro
        return redirect('membros'); //Redireciona para página inicial de membros
    }

    /**Método que repassa dados detalhados de membro
     * @param int $id identificador do membro
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function detalhar($id)
    {
        //Busca membro pelo id e repassa para método montarResposta formar o texto e retorna os dados
        return response($this->montarResposta(Membro::with('instituicao', 'endereco', 'telefones', 'emails')->find($id)));
    }

    /**Método privado que monta resposta com dados de membro em forma de texto
     * @param Membro $membro entidade cujos dados comporão o texto da resposta
     * @return string texto a ser retornado
     */
    private function montarResposta(Membro $membro)
    {
        $resposta = "Nome: $membro->nome"                                   //Cria variável passando nome do membro
            . "\nInstituição: " . $membro->instituicao->nome                //Passa nome da instituição
            . "\nCargo:  $membro->cargo"                                    //Passa cargo
            . "\nEndereço"                                                  //Passa string "Endereço"
            . "\nLogradouro: " . $membro->endereco->logradouro              //Passa logradouro
            . "\nNúmero: " . $membro->endereco->numero                      //Passa número
            . "\nComplemento: " . $membro->endereco->complemento            //Passa complemento
            . "\nBairro: " . $membro->endereco->bairro                      //Passa bairro
            . "\nCidade: " . $membro->endereco->cidade                      //Passa cidade
            . "-" . $membro->endereco->uf;                                  //Passa UF
        foreach ($membro->telefones as $telefone):                          //Percorre relação de telefones
            $resposta .= "\nTelefone: ($telefone->ddd)$telefone->numero";   //Passa dados do telefone formatado
        endforeach;
        foreach ($membro->emails as $email):                                //Percorre relação de e-mails
            $resposta .= "\nE-mail: $email->email";                         //Passa e-mail
        endforeach;
        return $resposta;                                                   //Retorna texto pronto
    }
}