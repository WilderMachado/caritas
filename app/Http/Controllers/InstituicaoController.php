<?php

namespace caritas\Http\Controllers;

use caritas\Email;
use caritas\Endereco;
use caritas\Http\Requests\InstituicaoRequest;
use caritas\Instituicao;
use caritas\Telefone;
use Illuminate\Http\Request;

class InstituicaoController extends Controller
{
    public function index()
    {
        $instituicoes = Instituicao::orderBy('nome')->paginate(config('constantes.paginacao'));
        return view('instituicao.index', compact('instituicoes'));
    }

    public function novo()
    {
        return view('instituicao.novo');
    }

    public function salvar(InstituicaoRequest $request)
    {
        $endereco = Endereco::create($request->endereco);
        $instituicao = new Instituicao($request->all());
        $instituicao->endereco()->associate($endereco);
        $instituicao->save();
        if ($request->get('telefones.ddd') && $request->get('telefones.numero')):
            $instituicao->telefones()->createMany($request->telefones);
        endif;
        if ($request->get('emails.email')):
            $instituicao->emails()->createMany($request->emails);
        endif;
        return redirect('instituicoes');
    }

    public function editar($id)
    {
        $instituicao = Instituicao::with('endereco', 'telefones', 'emails')->find($id);
        return view('instituicao.editar', compact('instituicao'));
    }

    public function alterar(InstituicaoRequest $request, $id)
    {
        $instituicao = Instituicao::with('endereco', 'telefones', 'emails')->find($id);
        $instituicao->endereco()->update($request->endereco);
        $telefones = array_values($request->telefones);
        if (count($telefones) == $instituicao->telefones->count()):
            $this->atualizarTelefones($instituicao, $telefones);
        elseif (count($telefones) > $instituicao->telefones->count()):
            $this->aumentarTelefones($instituicao, $telefones);
        else:
            $this->diminuirTelefones($instituicao, $telefones);
        endif;

        $emails = array_values($request->emails);
        if (count($emails) == $instituicao->emails->count()):
            $this->atualizarEmails($instituicao, $emails);
        elseif (count($emails) > $instituicao->emails->count()):
            $this->aumentarEmails($instituicao, $emails);
        else:
            $this->diminuirEmails($instituicao, $emails);
        endif;
        $instituicao->update($request->all());
        return redirect('instituicoes');
    }

    public function excluir($id)
    {
        $instituicao = Instituicao::find($id);
        $instituicao->membros()->delete();
        $instituicao->delete();
        return redirect('instituicoes');
    }

    private function atualizarTelefones(Instituicao $instituicao, $telefones)
    {
        foreach ($instituicao->telefones as $i => $telefone):
            $telefone->update($telefones[$i]);
        endforeach;
    }

    private function aumentarTelefones(Instituicao $instituicao, $telefones)
    {
        $this->atualizarTelefones($instituicao, array_slice($telefones, 0, $instituicao->telefones->count()));
        $instituicao->telefones()->createMany(array_slice($telefones, $instituicao->telefones->count()));
    }

    private function diminuirTelefones(Instituicao $instituicao, $telefones)
    {
        foreach ($telefones as $i => $telefone):
            $instituicao->telefones->get($i)->update($telefone);
        endforeach;
        Telefone::destroy(array_slice($instituicao->telefones->toArray(), count($telefones)));
    }

    private function atualizarEmails(Instituicao $instituicao, $emails)
    {
        foreach ($instituicao->emails as $i => $email):
            $email->update($emails[$i]);
        endforeach;
    }

    private function aumentarEmails(Instituicao $instituicao, $emails)
    {
        $this->atualizarEmails($instituicao, array_slice($emails, 0, $instituicao->emails->count()));
        $instituicao->emails()->createMany(array_slice($emails, $instituicao->emails->count()));
    }

    private function diminuirEmails(Instituicao $instituicao, $emails)
    {
        foreach ($emails as $i => $email):
            $instituicao->emails->get($i)->update($email);
        endforeach;
        Email::destroy(array_slice($instituicao->emails->toArray(), count($emails)));
    }
}