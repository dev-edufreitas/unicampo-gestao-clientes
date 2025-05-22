<?php

// app/Http/Controllers/API/ClienteController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Endereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    /**
     * @OA\Get(
     *      path="/clientes",
     *      operationId="getClientesList",
     *      tags={"Clientes"},
     *      summary="Obter lista de clientes",
     *      description="Retorna a lista de clientes com filtros e ordenação",
     *      @OA\Parameter(
     *          name="status",
     *          in="query",
     *          description="Filtrar por status (ativo/inativo)",
     *          required=false,
     *          @OA\Schema(type="string", enum={"ativo", "inativo"})
     *      ),
     *      @OA\Parameter(
     *          name="nome",
     *          in="query",
     *          description="Buscar por nome",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="order_desc",
     *          in="query",
     *          description="Ordenar por data de criação decrescente",
     *          required=false,
     *          @OA\Schema(type="boolean")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Lista de clientes",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Cliente"))
     *      )
     * )
     */
    public function index(Request $request)
    {
        $query = Cliente::with(['endereco', 'profissao']);
        
        // Filtro por status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Busca por nome
        if ($request->has('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }
        
        // Ordenação por data de criação
        $orderDirection = $request->has('order_desc') && $request->order_desc ? 'desc' : 'asc';
        $query->orderBy('created_at', $orderDirection);
        
        return response()->json($query->get());
    }
    
        /**
     * @OA\Post(
     *      path="/api/v1/clientes",
     *      operationId="storeCliente",
     *      tags={"Clientes"},
     *      summary="Cadastrar novo cliente",
     *      description="Cria um novo cliente com endereço",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ClienteRequest")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Cliente cadastrado com sucesso",
     *          @OA\JsonContent(ref="#/components/schemas/ClienteResponse")
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Erro de validação"
     *      )
     * )
     */
    public function store(Request $request)
    {
        // Validação dos dados pessoais (Etapa 1)
        $validatorDadosPessoais = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'tipo_pessoa' => 'required|in:fisica,juridica',
            'documento' => [
                'required',
                'string',
                Rule::unique('clientes', 'documento'),
                function ($attribute, $value, $fail) use ($request) {
                    // Validação do CPF/CNPJ
                    $value = preg_replace('/[^0-9]/', '', $value);
                    
                    if ($request->tipo_pessoa === 'fisica' && !$this->validaCPF($value)) {
                        $fail('O CPF informado é inválido.');
                    } elseif ($request->tipo_pessoa === 'juridica' && !$this->validaCNPJ($value)) {
                        $fail('O CNPJ informado é inválido.');
                    }
                },
            ],
            'email' => 'required|email|unique:clientes,email',
            'telefone' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Validação do telefone (8 ou 9 dígitos + DDD)
                    $telefone = preg_replace('/[^0-9]/', '', $value);
                    if (strlen($telefone) < 10 || strlen($telefone) > 11) {
                        $fail('O telefone deve conter 8 ou 9 dígitos mais o DDD.');
                    }
                },
            ],
        ]);
        
        if ($validatorDadosPessoais->fails()) {
            return response()->json(['errors' => $validatorDadosPessoais->errors()], 422);
        }
        
        // Validação do endereço (Etapa 2)
        $validatorEndereco = Validator::make($request->all(), [
            'endereco' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'bairro' => 'required|string|max:255',
            'complemento' => 'nullable|string|max:255',
            'cidade' => 'required|string|max:255',
            'uf' => 'required|string|size:2',
        ]);
        
        if ($validatorEndereco->fails()) {
            return response()->json(['errors' => $validatorEndereco->errors()], 422);
        }
        
        // Validação da profissão (Etapa 3)
        $validatorProfissao = Validator::make($request->all(), [
            'id_profissao' => 'required|exists:profissoes,id',
        ]);
        
        if ($validatorProfissao->fails()) {
            return response()->json(['errors' => $validatorProfissao->errors()], 422);
        }
        
        // Criação do endereço
        $endereco = Endereco::create([
            'endereco' => $request->endereco,
            'numero' => $request->numero,
            'bairro' => $request->bairro,
            'complemento' => $request->complemento,
            'cidade' => $request->cidade,
            'uf' => $request->uf,
        ]);
        
        // Criação do cliente
        $cliente = Cliente::create([
            'nome' => $request->nome,
            'data_nascimento' => $request->data_nascimento,
            'tipo_pessoa' => $request->tipo_pessoa,
            'documento' => $this->formatarDocumento($request->documento, $request->tipo_pessoa),
            'email' => $request->email,
            'telefone' => $this->formatarTelefone($request->telefone),
            'id_endereco' => $endereco->id,
            'id_profissao' => $request->id_profissao,
            'status' => 'ativo',
        ]);
        
        return response()->json([
            'message' => 'Cliente cadastrado com sucesso',
            'cliente' => $cliente->load(['endereco', 'profissao']),
        ], 201);
    }
    
        /**
     * @OA\Get(
     *      path="/api/v1/clientes/{id}",
     *      operationId="getClienteById",
     *      tags={"Clientes"},
     *      summary="Obter dados de um cliente",
     *      description="Retorna os dados de um cliente específico",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID do cliente",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Dados do cliente",
     *          @OA\JsonContent(ref="#/components/schemas/Cliente")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Cliente não encontrado"
     *      )
     * )
     */
    public function show($id)
    {
        $cliente = Cliente::with(['endereco', 'profissao'])->find($id);
        
        if (!$cliente) {
            return response()->json(['error' => 'Cliente não encontrado'], 404);
        }
        
        return response()->json($cliente);
    }
    
    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);
        
        if (!$cliente) {
            return response()->json(['error' => 'Cliente não encontrado'], 404);
        }
        
        // Validação dos dados pessoais (Etapa 1)
        $validatorDadosPessoais = Validator::make($request->all(), [
            'nome'            => 'required|string|max:255',
            'data_nascimento' => 'required|date',
            'tipo_pessoa'     => 'required|in:fisica,juridica',
            'documento' => [
                'required',
                'string',
                Rule::unique('clientes', 'documento')->ignore($cliente->id),
                function ($attribute, $value, $fail) use ($request) {
                    // Validação do CPF/CNPJ
                    $value = preg_replace('/[^0-9]/', '', $value);
                    
                    if ($request->tipo_pessoa === 'fisica' && !$this->validaCPF($value)) {
                        $fail('O CPF informado é inválido.');
                    } elseif ($request->tipo_pessoa === 'juridica' && !$this->validaCNPJ($value)) {
                        $fail('O CNPJ informado é inválido.');
                    }
                },
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('clientes', 'email')->ignore($cliente->id),
            ],
            'telefone' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Validação do telefone (8 ou 9 dígitos + DDD)
                    $telefone = preg_replace('/[^0-9]/', '', $value);
                    if (strlen($telefone) < 10 || strlen($telefone) > 11) {
                        $fail('O telefone deve conter 8 ou 9 dígitos mais o DDD.');
                    }
                },
            ],
            'status' => 'required|in:ativo,inativo',
        ]);
        
        if ($validatorDadosPessoais->fails()) {
            return response()->json(['errors' => $validatorDadosPessoais->errors()], 422);
        }
        
        // Validação do endereço (Etapa 2)
        $validatorEndereco = Validator::make($request->all(), [
            'endereco'    => 'required|string|max:255',
            'numero'      => 'required|string|max:20',
            'bairro'      => 'required|string|max:255',
            'complemento' => 'nullable|string|max:255',
            'cidade'      => 'required|string|max:255',
            'uf'          => 'required|string|size:2',
        ]);
        
        if ($validatorEndereco->fails()) {
            return response()->json(['errors' => $validatorEndereco->errors()], 422);
        }
        
        // Validação da profissão (Etapa 3)
        $validatorProfissao = Validator::make($request->all(), [
            'id_profissao' => 'required|exists:profissoes,id',
        ]);
        
        if ($validatorProfissao->fails()) {
            return response()->json(['errors' => $validatorProfissao->errors()], 422);
        }
        
        // Atualização do endereço
        $endereco = $cliente->endereco;
        $endereco->update([
            'endereco'    => $request->endereco,
            'numero'      => $request->numero,
            'bairro'      => $request->bairro,
            'complemento' => $request->complemento,
            'cidade'      => $request->cidade,
            'uf'          => $request->uf,
        ]);
        
        // Atualização do cliente
        $cliente->update([
            'nome'            => $request->nome,
            'data_nascimento' => $request->data_nascimento,
            'tipo_pessoa'     => $request->tipo_pessoa,
            'documento'       => $this->formatarDocumento($request->documento, $request->tipo_pessoa),
            'email'           => $request->email,
            'telefone'        => $this->formatarTelefone($request->telefone),
            'id_profissao'    => $request->id_profissao,
            'status'          => $request->status,
        ]);
        
        return response()->json([
            'message' => 'Cliente atualizado com sucesso',
            'cliente' => $cliente->fresh(['endereco', 'profissao']),
        ]);
    }
    
    public function destroy($id)
    {
        $cliente = Cliente::find($id);
        
        if (!$cliente) {
            return response()->json(['error' => 'Cliente não encontrado'], 404);
        }
        
        // Atualizar status para inativo em vez de excluir
        $cliente->update(['status' => 'inativo']);
        
        return response()->json(['message' => 'Cliente inativado com sucesso']);
    }
    
    // Funções auxiliares para validação e formatação
    
    private function validaCPF($cpf)
    {
        // Remove os caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        // Verifica se o CPF tem 11 dígitos
        if (strlen($cpf) != 11) {
            return false;
        }
        
        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        
        // Calcula o primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $dv1 = ($resto < 2) ? 0 : 11 - $resto;
        
        // Calcula o segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $dv2 = ($resto < 2) ? 0 : 11 - $resto;
        
        // Verifica se os dígitos verificadores estão corretos
        return ($cpf[9] == $dv1 && $cpf[10] == $dv2);
    }
    
    private function validaCNPJ($cnpj)
    {
        // Remove os caracteres não numéricos
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        
        // Verifica se o CNPJ tem 14 dígitos
        if (strlen($cnpj) != 14) {
            return false;
        }
        
        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }
        
        // Calcula o primeiro dígito verificador
        $multiplicadores = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $soma = 0;
        for ($i = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $multiplicadores[$i];
        }
        $resto = $soma % 11;
        $dv1 = ($resto < 2) ? 0 : 11 - $resto;
        
        // Calcula o segundo dígito verificador
        $multiplicadores = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $soma = 0;
        for ($i = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $multiplicadores[$i];
        }
        $resto = $soma % 11;
        $dv2 = ($resto < 2) ? 0 : 11 - $resto;
        
        // Verifica se os dígitos verificadores estão corretos
        return ($cnpj[12] == $dv1 && $cnpj[13] == $dv2);
    }
    
    private function formatarDocumento($documento, $tipoPessoa)
    {
        // Remove os caracteres não numéricos
        $documento = preg_replace('/[^0-9]/', '', $documento);
        
        if ($tipoPessoa === 'fisica') {
            // Formato: 000.000.000-00
            return substr($documento, 0, 3) . '.' .
                   substr($documento, 3, 3) . '.' .
                   substr($documento, 6, 3) . '-' .
                   substr($documento, 9, 2);
        } else {
            // Formato: 00.000.000/0000-00
            return substr($documento, 0, 2) . '.' .
                   substr($documento, 2, 3) . '.' .
                   substr($documento, 5, 3) . '/' .
                   substr($documento, 8, 4) . '-' .
                   substr($documento, 12, 2);
        }
    }
    
    private function formatarTelefone($telefone)
    {
        // Remove os caracteres não numéricos
        $telefone = preg_replace('/[^0-9]/', '', $telefone);
        
        // Formato: (XX) XXXXX-XXXX para celular ou (XX) XXXX-XXXX para fixo
        $ddd = substr($telefone, 0, 2);
        
        if (strlen($telefone) === 11) {
            // Celular com 9 dígitos
            $parte1 = substr($telefone, 2, 5);
            $parte2 = substr($telefone, 7, 4);
            return "({$ddd}) {$parte1}-{$parte2}";
        } else {
            // Telefone fixo com 8 dígitos
            $parte1 = substr($telefone, 2, 4);
            $parte2 = substr($telefone, 6, 4);
            return "({$ddd}) {$parte1}-{$parte2}";
        }
    }
}