<?php

namespace caritas\Http\Controllers;

use caritas\Email;
use caritas\Endereco;
use caritas\Http\Requests\InstituicaoRequest;
use caritas\Instituicao;
use caritas\Telefone;

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
        $instituicoes = Instituicao::orderBy('nome')                //Busca instituições e ordena por nome
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
        $endereco = Endereco::create($request->endereco);                           //Cria endereço
        $instituicao = new Instituicao($request->all());                            //Instancia nova instituição com dados passados
        $instituicao->endereco()->associate($endereco);                             //Associa endereço a instituição
        $instituicao->save();                                                       //Salva instituição
        if ($request->get('telefones.ddd') && $request->get('telefones.numero')):   //Verifica se foi passado algum telefone
            $instituicao->telefones()->createMany($request->telefones);             //Cria telefones
        endif;
        if ($request->get('emails.email')):                                         //Verifica se foi passado algum e-mail
            $instituicao->emails()->createMany($request->emails);                   //Cria e-mails
        endif;
        return redirect('instituicoes');                                            //Redireciona para página inicial de instituições
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
        $telefones = array_values($request->telefones);                                 //Passa para variável relação de telefones da edição
        if (count($telefones) == $instituicao->telefones->count()):                     //Verifica se quantidade de telefones é a mesma
            $this->atualizarTelefones($instituicao, $telefones);                        //Atualiza dados de telefones por meio do método atualizarTelefones
        elseif (count($telefones) > $instituicao->telefones->count()):                  //Se quantidade de telefones aumentou
            $this->aumentarTelefones($instituicao, $telefones);                         //Aumenta os telefones por meio do método aumentarTelefones
        else:                                                                           //Caso quantidade de telefones tenha diminuido
            $this->diminuirTelefones($instituicao, $telefones);                         //Diminui os telefones com o método diminuirTelefones
        endif;
        $emails = array_values($request->emails);                                       //Passa para variável relação de e-mails da edição
        if (count($emails) == $instituicao->emails->count()):                           //Verifica se quantidade de e-mail é a mesma
            $this->atualizarEmails($instituicao, $emails);                              //Atualiza dados de e-mail por meio do método atualizarEmails
        elseif (count($emails) > $instituicao->emails->count()):                        //Se quantidade de e-mail aumentou
            $this->aumentarEmails($instituicao, $emails);                               //Aumento os e-mail por meio do método aumentarEmails
        else:                                                                           //Caso quantidade de e-mails tenha diminuido
            $this->diminuirEmails($instituicao, $emails);                               //Diminui os e-mail com o método diminuirEmails
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

    /**Método privado que atualiza dados de telefones
     * @param Instituicao $instituicao dona dos telefones
     * @param array $telefones relação de telefones
     */
    private function atualizarTelefones(Instituicao $instituicao, $telefones)
    {
        foreach ($instituicao->telefones as $i => $telefone):   //Percorre relação de telefones da instituição
            $telefone->update($telefones[$i]);                  //Altera dados
        endforeach;
    }

    /**Método privado que aumenta relação de telefones
     * @param Instituicao $instituicao dona dos telefones
     * @param array $telefones relação de telefones
     */
    private function aumentarTelefones(Instituicao $instituicao, $telefones)
    {
        //Atualiza telefones já existente, por meio do método atualizarTelefones (até o número já existente)
        $this->atualizarTelefones($instituicao, array_slice($telefones, 0, $instituicao->telefones->count()));
        //Cria novos telefones (a partir da quantidade existente)
        $instituicao->telefones()->createMany(array_slice($telefones, $instituicao->telefones->count()));
    }

    /**Método privado que reduz relação de telefones
     * @param Instituicao $instituicao dona dos telefones
     * @param array $telefones relação de telefenes
     */
    private function diminuirTelefones(Instituicao $instituicao, $telefones)
    {
        foreach ($telefones as $i => $telefone):                                                //Percorre relação de telefones passada
            $instituicao->telefones->get($i)->update($telefone);                                //Atualiza telefones já existentes
        endforeach;
        Telefone::destroy(array_slice($instituicao->telefones->toArray(), count($telefones)));  //Exclui telefones sobrando
    }

    /**Método privado que atualiza dados de e-mails
     * @param Instituicao $instituicao dona dos e-mail
     * @param array $emails relação de e-mails
     */
    private function atualizarEmails(Instituicao $instituicao, $emails)
    {
        foreach ($instituicao->emails as $i => $email): //Percorre relação de e-mails da instituição
            $email->update($emails[$i]);                //Altera dados
        endforeach;
    }

    /** Método privado que aumenta relação de e-mail
     * @param Instituicao $instituicao dona dos e-mails
     * @param array $emails relação de e-mails
     */
    private function aumentarEmails(Instituicao $instituicao, $emails)
    {
        //Atualiza e-mails já existente, por meio do método atualizarEmails (até o número já existente)
        $this->atualizarEmails($instituicao, array_slice($emails, 0, $instituicao->emails->count()));
        //Cria novos e-mails (a partir da quantidade existente)
        $instituicao->emails()->createMany(array_slice($emails, $instituicao->emails->count()));
    }

    /**Método privado que reduz relação de e-mails
     * @param Instituicao $instituicao dona dos e-mails
     * @param array $emails relação de e-mails
     */
    private function diminuirEmails(Instituicao $instituicao, $emails)
    {
        foreach ($emails as $i => $email):                                              //Percorre relação de e-mails passada
            $instituicao->emails->get($i)->update($email);                              //Atualiza e-mails já existentes
        endforeach;
        Email::destroy(array_slice($instituicao->emails->toArray(), count($emails)));   //Exclui e-mail sobrando
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