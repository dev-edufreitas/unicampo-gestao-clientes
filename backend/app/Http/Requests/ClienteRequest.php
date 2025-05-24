<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Utils\DocumentoValidator;

class ClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $clienteId = $this->route('id'); 

        return [
            'nome' => [
                'required',
                'string',
                'max:255'
            ],
            'data_nascimento' => [
                'required',
                'date',
                'before:today'
            ],
            'tipo_pessoa' => [
                'required',
                'in:fisica,juridica'
            ],
            'documento' => [
                'required',
                'string',
                Rule::unique('clientes', 'documento')->ignore($clienteId),
                function ($attribute, $value, $fail) {
                    $documento = preg_replace('/[^0-9]/', '', $value);
                    $tipoPessoa = $this->input('tipo_pessoa');
                    
                    if (!DocumentoValidator::validarDocumento($documento, $tipoPessoa)) {
                        $tipoDoc = $tipoPessoa === 'fisica' ? 'CPF' : 'CNPJ';
                        $fail("O {$tipoDoc} informado é inválido.");
                    }
                },
            ],
            'email' => 'required|email|unique:clientes,email,' . $this->route('id'),
            'telefone' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $telefone = preg_replace('/[^0-9]/', '', $value);
                    if (strlen($telefone) < 10 || strlen($telefone) > 11) {
                        $fail('O telefone deve conter DDD + 8 ou 9 dígitos.');
                    }
                },
            ],
            'endereco' => [
                'required',
                'string',
                'max:255'
            ],
            'numero' => [
                'required',
                'string',
                'max:20'
            ],
            'bairro' => [
                'required',
                'string',
                'max:255'
            ],
            'complemento' => [
                'nullable',
                'string',
                'max:255'
            ],
            'cidade' => [
                'required',
                'string',
                'max:255'
            ],
            'uf' => [
                'required',
                'string',
                'size:2',
                'uppercase'
            ],
            'id_profissao' => [
                'required',
                'integer',
                'exists:profissoes,id'
            ],
            'status' => [
                'sometimes',
                'required',
                'in:ativo,inativo'
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            // Dados Pessoais
            'nome.required' => 'O nome é obrigatório.',
            'nome.string'   => 'O nome deve ser um texto válido.',
            'nome.max'      => 'O nome deve ter no máximo 255 caracteres.',
            
            'data_nascimento.required' => 'A data de nascimento é obrigatória.',
            'data_nascimento.date'     => 'A data de nascimento deve ser uma data válida.',
            'data_nascimento.before'   => 'A data de nascimento deve ser anterior à data atual.',
            
            'tipo_pessoa.required' => 'O tipo de pessoa é obrigatório.',
            'tipo_pessoa.in'       => 'O tipo de pessoa deve ser física ou jurídica.',
            
            'documento.required' => 'O documento é obrigatório.',
            'documento.string'   => 'O documento deve ser um texto válido.',
            'documento.unique'   => 'Este documento já está cadastrado no sistema.',
            
            'email.required' => 'O email é obrigatório.',
            'email.email'    => 'O email deve ter um formato válido.',
            'email.unique'   => 'Este email já está cadastrado no sistema.',
            
            'telefone.required' => 'O telefone é obrigatório.',
            'telefone.string'   => 'O telefone deve ser um texto válido.',
            
              // Endereço
            'endereco.required' => 'O endereço é obrigatório.',
            'endereco.string'   => 'O endereço deve ser um texto válido.',
            'endereco.max'      => 'O endereço deve ter no máximo 255 caracteres.',
            
            'numero.required' => 'O número é obrigatório.',
            'numero.string'   => 'O número deve ser um texto válido.',
            'numero.max'      => 'O número deve ter no máximo 20 caracteres.',
            
            'bairro.required' => 'O bairro é obrigatório.',
            'bairro.string'   => 'O bairro deve ser um texto válido.',
            'bairro.max'      => 'O bairro deve ter no máximo 255 caracteres.',
            
            'complemento.string' => 'O complemento deve ser um texto válido.',
            'complemento.max'    => 'O complemento deve ter no máximo 255 caracteres.',
            
            'cidade.required' => 'A cidade é obrigatória.',
            'cidade.string'   => 'A cidade deve ser um texto válido.',
            'cidade.max'      => 'A cidade deve ter no máximo 255 caracteres.',
            
            'uf.required'  => 'O estado (UF) é obrigatório.',
            'uf.string'    => 'O estado deve ser um texto válido.',
            'uf.size'      => 'O estado deve ter exatamente 2 caracteres.',
            'uf.uppercase' => 'O estado deve estar em letras maiúsculas.',
            
              // Profissão
            'id_profissao.required' => 'A profissão é obrigatória.',
            'id_profissao.integer'  => 'A profissão deve ser um número válido.',
            'id_profissao.exists'   => 'A profissão selecionada não existe.',
            
              // Status
            'status.required' => 'O status é obrigatório.',
            'status.in'       => 'O status deve ser ativo ou inativo.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nome'            => 'nome',
            'data_nascimento' => 'data de nascimento',
            'tipo_pessoa'     => 'tipo de pessoa',
            'documento'       => 'documento',
            'email'           => 'email',
            'telefone'        => 'telefone',
            'endereco'        => 'endereço',
            'numero'          => 'número',
            'bairro'          => 'bairro',
            'complemento'     => 'complemento',
            'cidade'          => 'cidade',
            'uf'              => 'estado',
            'id_profissao'    => 'profissão',
            'status'          => 'status',
        ];
    }


    protected function prepareForValidation(): void
    {
        if ($this->has('uf')) {
            $this->merge([
                'uf' => strtoupper($this->uf)
            ]);
        }

        if ($this->has('documento')) {
            $this->merge([
                'documento_limpo' => preg_replace('/[^0-9]/', '', $this->documento)
            ]);
        }
    }


    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->has('telefone')) {
                $telefone = preg_replace('/[^0-9]/', '', $this->telefone);
                $ddd = substr($telefone, 0, 2);
                
                $dddsValidos = [
                    '11', '12', '13', '14', '15', '16', '17', '18', '19', // SP
                    '21', '22', '24', // RJ
                    '27', '28', // ES
                    '31', '32', '33', '34', '35', '37', '38', // MG
                    '41', '42', '43', '44', '45', '46', // PR
                    '47', '48', '49', // SC
                    '51', '53', '54', '55', // RS
                    '61', // DF
                    '62', '64', // GO
                    '63', // TO
                    '65', '66', // MT
                    '67', // MS
                    '68', // AC
                    '69', // RO
                    '71', '73', '74', '75', '77', // BA
                    '79', // SE
                    '81', '87', // PE
                    '82', // AL
                    '83', // PB
                    '84', // RN
                    '85', '88', // CE
                    '86', '89', // PI
                    '91', '93', '94', // PA
                    '92', '97', // AM
                    '95', // RR
                    '96', // AP
                    '98', '99', // MA
                ];
                
                if (!in_array($ddd, $dddsValidos)) {
                    $validator->errors()->add('telefone', 'O DDD do telefone não é válido.');
                }
            }
        });
    }
}