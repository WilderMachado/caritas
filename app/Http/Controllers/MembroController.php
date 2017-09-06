<?php

namespace caritas\Http\Controllers;

use caritas\Email;
use caritas\Endereco;
use caritas\Http\Requests\MembroRequest;
use caritas\Instituicao;
use caritas\Membro;
use caritas\Telefone;

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
        $membros = Membro::with('instituicao', 'telefones', 'emails')   //Busca membros
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
        $endereco = Endereco::create($request->endereco);                           //Cria endereço
        $membro = new Membro($request->all());                                      //Instancia novo membro com dados passados
        $membro->endereco()->associate($endereco);                                  //Associa endereço a membro
        $membro->save();                                                            //Salva membro
        if ($request->get('telefones.ddd') && $request->get('telefones.numero')):   //Verifica se foi passado algum telefone
            $membro->telefones()->create($request->telefone);                       //Cria telefones
        endif;
        if ($request->get('emails.email')):                                         //Verifica se foi passado algum e-mail
            $membro->emails()->create($request->email);                             //Cria e-mails
        endif;
        return redirect('membros');                                                 //Redireciona para página inicial de membros
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
        $telefones = array_values($request->telefones);                                     //Passa para variável relação de telefones
        if (count($telefones) == $membro->telefones->count()):                              //Verifica se quantidade de telefones é a mesma
            $this->atualizarTelefones($membro, $telefones);                                 //Atualiza dados de telefones por meio do método atualizarTelefones
        elseif (count($telefones) > $membro->telefones->count()):                           //Se quantidade de telefone aumentou
            $this->aumentarTelefones($membro, $telefones);                                  //Aumenta os telefones por meio do método aumentarTelefeones
        else:                                                                               //Caso quantidade de telefones tenha diminuido
            $this->diminuirTelefones($membro, $telefones);                                  //Diminui os telefones com o método diminuirTelefones
        endif;
        $emails = array_values($request->emails);                                           //Passa para variável relação de e-mail
        if (count($emails) == $membro->emails->count()):                                    //Verifica se quantidade de e-mail é a mesma
            $this->atualizarEmails($membro, $emails);                                       //Atualiza dados de e-mails por meio do método atualizarEmails
        elseif (count($emails) > $membro->emails->count()):                                 //Se quantidade de e-mail aumentou
            $this->aumentarEmails($membro, $emails);                                        //Aumenta os e-mails por meio do método aumentarEmails
        else:                                                                               //Caso quantidade de e-mail tenha diminuido
            $this->diminuirEmails($membro, $emails);                                        //Diminui os e-mails com o método diminuirEmails
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

    /**Método privado para atualizar dados de telefones
     * @param Membro $membro dono dos telefones
     * @param array $telefones relação de telefones
     */
    private function atualizarTelefones(Membro $membro, $telefones)
    {
        foreach ($membro->telefones as $i => $telefone)://Percorre relação de telefones do membro
            $telefone->update($telefones[$i]);          //Altera dados
        endforeach;
    }

    /**Método privado que aumenta relação de telefones
     * @param Membro $membro dono dos telefones
     * @param array $telefones relação de telefones
     */
    private function aumentarTelefones(Membro $membro, $telefones)
    {
        //Atualiza telefones já existente, por meio do método atualizarTelefones (até o número já existente)
        $this->atualizarTelefones($membro, array_slice($telefones, 0, $membro->telefones->count()));
        //Cria novos telefones (a partir da quantidade existente)
        $membro->telefones()->createMany(array_slice($telefones, $membro->telefones->count()));
    }

    /**Método privado que reduz relação de telefones
     * @param Membro $membro dono dos telefones
     * @param array $telefones relação de telefones
     */
    private function diminuirTelefones(Membro $membro, $telefones)
    {
        foreach ($telefones as $i => $telefone):                                            //Percorre relação de telefones passada
            $membro->telefones->get($i)->update($telefone);                                 //Atualiza telefones já existentes
        endforeach;
        Telefone::destroy(array_slice($membro->telefones->toArray(), count($telefones)));   //Exclui telefones sobrando
    }

    /**Método privado que atualiza dados de e-mails
     * @param Membro $membro dono dos e-mails
     * @param array $emails relação de e-mails
     */
    private function atualizarEmails(Membro $membro, $emails)
    {
        foreach ($membro->emails as $i => $email):  //Percorre relação de e-mails do membro
            $email->update($emails[$i]);            //Altera dados
        endforeach;
    }

    /**Método privado que aumenta relação de e-mails
     * @param Membro $membro dono dos e-mails
     * @param array $emails relação de e-mails
     */
    private function aumentarEmails(Membro $membro, $emails)
    {
        //Atualiza e-mails já existente, por meio do método atualizarEmails (até o número já existente)
        $this->atualizarEmails($membro, array_slice($emails, 0, $membro->emails->count()));
        //Cria novos e-mails (a partir da quantidade existente)
        $membro->emails()->createMany(array_slice($emails, $membro->emails->count()));
    }

    /**Método privado que reduz relação de e-mails
     * @param Membro $membro dono dos e-mails
     * @param array $emails relação de e-mails
     */

    private function diminuirEmails(Membro $membro, $emails)
    {
        foreach ($emails as $i => $email):                                      //Percorre relação de e-mail passada
            $membro->emails->get($i)->update($email);                           //Atualiza e-mail já existentes
        endforeach;
        Email::destroy(array_slice($membro->emails->toArray(), count($emails)));//Exclui e-mails sobrando
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