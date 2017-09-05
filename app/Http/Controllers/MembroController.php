<?php

namespace caritas\Http\Controllers;

use caritas\Email;
use caritas\Endereco;
use caritas\Http\Requests\MembroRequest;
use caritas\Instituicao;
use caritas\Membro;
use caritas\Telefone;

class MembroController extends Controller
{

    public function index()
    {
        $membros = Membro::with('instituicao', 'telefones', 'emails')->orderBy('nome')->paginate(config('constantes.paginacao'));
        return view('membro.index', compact('membros'));
    }

    public function novo()
    {
        $instituicoes = Instituicao::orderBy('nome')->pluck('nome', 'id');
        return view('membro.novo', compact('instituicoes'));
    }

    public function salvar(MembroRequest $request)
    {
        $endereco = Endereco::create($request->endereco);
        $membro = new Membro($request->all());
        $membro->endereco()->associate($endereco);
        $membro->save();
        if ($request->get('telefones.ddd') && $request->get('telefones.numero')):
            $membro->telefones()->create($request->telefone);
        endif;
        if ($request->get('emails.email')):
            $membro->emails()->create($request->email);
        endif;
        return redirect('membros');
    }

    public function editar($id)
    {
        $membro = Membro::with('instituicao', 'endereco', 'telefones', 'emails')->find($id);
        $instituicoes = Instituicao::orderBy('nome')->pluck('nome', 'id');
        return view('membro.editar', compact('membro', 'instituicoes'));
    }

    public function alterar(MembroRequest $request, $id)
    {
        $membro = Membro::with('instituicao', 'endereco', 'telefones', 'emails')->find($id);
        $membro->endereco->update($request->endereco);
        $telefones = array_values($request->telefones);
        if (count($telefones) == $membro->telefones->count()):
            $this->atualizarTelefones($membro, $telefones);
        elseif (count($telefones) > $membro->telefones->count()):
            $this->aumentarTelefones($membro, $telefones);
        else:
            $this->diminuirTelefones($membro, $telefones);
        endif;
        $emails = array_values($request->emails);
        if (count($emails) == $membro->emails->count()):
            $this->atualizarEmails($membro, $emails);
        elseif (count($emails) > $membro->emails->count()):
            $this->aumentarEmails($membro, $emails);
        else:
            $this->diminuirEmails($membro, $emails);
        endif;
        $membro->update($request->all());
        return redirect('membros');
    }

    public function excluir($id)
    {
        Membro::destroy($id);
        return redirect('membros');
    }

    public function detalhar($id)
    {
        return response($this->montarResposta(Membro::with('instituicao', 'endereco', 'telefones', 'emails')->find($id)));
    }

    private function atualizarTelefones(Membro $membro, $telefones)
    {
        foreach ($membro->telefones as $i => $telefone):
            $telefone->update($telefones[$i]);
        endforeach;
    }

    private function aumentarTelefones(Membro $membro, $telefones)
    {
        $this->atualizarTelefones($membro, array_slice($telefones, 0, $membro->telefones->count()));
        $membro->telefones()->createMany(array_slice($telefones, $membro->telefones->count()));
    }

    private function diminuirTelefones(Membro $membro, $telefones)
    {
        foreach ($telefones as $i => $telefone):
            $membro->telefones->get($i)->update($telefone);
        endforeach;
        Telefone::destroy(array_slice($membro->telefones->toArray(), count($telefones)));
    }

    private function atualizarEmails(Membro $membro, $emails)
    {
        foreach ($membro->emails as $i => $email):
            $email->update($emails[$i]);
        endforeach;
    }

    private function aumentarEmails(Membro $membro, $emails)
    {
        $this->atualizarEmails($membro, array_slice($emails, 0, $membro->emails->count()));
        $membro->emails()->createMany(array_slice($emails, $membro->emails->count()));
    }

    private function diminuirEmails(Membro $membro, $emails)
    {
        foreach ($emails as $i => $email):
            $membro->emails->get($i)->update($email);
        endforeach;
        Email::destroy(array_slice($membro->emails->toArray(), count($emails)));
    }

    private function montarResposta(Membro $membro)
    {
        $resposta = "Nome: $membro->nome"
            . "\nInstituição: " . $membro->instituicao->nome
            . "\nCargo:  $membro->cargo"
            . "\nEndereço"
            . "\nLogradouro: " . $membro->endereco->logradouro
            . "\nNúmero: " . $membro->endereco->numero
            . "\nComplemento: " . $membro->endereco->complemento
            . "\nBairro: " . $membro->endereco->bairro
            . "\nCidade: " . $membro->endereco->cidade
            . "-" . $membro->endereco->uf;
        foreach ($membro->telefones as $telefone):
            $resposta .= "\nTelefone: ($telefone->ddd)$telefone->numero";
        endforeach;
        foreach ($membro->emails as $email):
            $resposta .= "\nE-mail: $email->email";
        endforeach;
        return $resposta;
    }
}