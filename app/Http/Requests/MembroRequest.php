<?php

namespace caritas\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MembroRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => 'required|max:255',
            'cargo'=>'required|max:255',
            'endereco.logradouro' => 'required|max:255',
            'endereco.numero' => 'integer|nullable',
            'endereco.complemento' => 'max:255',
            'endereco.bairro' => 'max:255',
            'endereco.cidade' => 'required|max:255',
            'endereco.uf' => 'required|max:255',
            'telefones.*.ddd' => 'integer|nullable|digits:2|required_with:telefones.*.numero,telefones.*.tipo',
            'telefones.*.numero' => 'integer|nullable|digits_between:8,9|required_with:telefones.*.ddd,telefones.*.tipo',
            'telefones.*.tipo' => 'required_with:telefones.*.ddd,telefones.*.numero',
            'emails.*.email' => 'email|nullable',
        ];
    }
    public function attributes()
    {
        return [
            'endereco.logradouro' => 'Logradouro',
            'endereco.numero' => 'Número do imóvel',
            'endereco.complemento' => 'complemento do endereço',
            'endereco.bairro' => 'bairro',
            'endereco.cidade' => 'cidade',
            'endereco.uf' => 'UF',
            'emails.*.email' => 'email',
            'telefones.*.ddd' => 'DDD',
            'telefones.*.numero' => 'número do telefone',
            'telefones.*.tipo' => 'tipo de telefone',
        ];
    }
}
