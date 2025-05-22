<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClienteRequest;
use App\Services\ClienteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function __construct(
        private ClienteService $clienteService
    ) {}

    /**
     * @OA\Get(
     *      path="/clientes/stats",
     *      operationId="getClientesStats",
     *      tags={"Clientes"},
     *      summary="Obter estatísticas dos clientes",
     *      description="Retorna as 3 estatísticas principais: Total, Ativos e Novos do Mês",
     *      @OA\Response(
     *          response=200,
     *          description="Estatísticas dos clientes",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="total_clientes", type="string", example="1,247"),
     *              @OA\Property(property="clientes_ativos", type="string", example="1,089"),
     *              @OA\Property(property="novos_mes", type="string", example="43")
     *          )
     *      )
     * )
     */
    public function getStats(): JsonResponse
    {
        try {
            $stats = $this->clienteService->obterEstatisticas();
            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao obter estatísticas'], 500);
        }
    }

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
    public function index(Request $request): JsonResponse
    {
        $filtros = $request->only(['status', 'nome', 'order_desc']);
        $clientes = $this->clienteService->buscarClientes($filtros);

        return response()->json($clientes);
    }

    /**
     * @OA\Post(
     *      path="/clientes",
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
    public function store(ClienteRequest $request): JsonResponse
    {
        try {
            $cliente = $this->clienteService->criarCliente($request->validated());

            // Limpar cache das estatísticas
            $this->clienteService->limparCacheStats();

            return response()->json([
                'message' => 'Cliente cadastrado com sucesso',
                'cliente' => $cliente,
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Erro ao criar cliente', [
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);

            return response()->json([
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/clientes/{id}",
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
    public function show(int $id): JsonResponse
    {
        $cliente = $this->clienteService->buscarClientePorId($id);

        if (!$cliente) {
            return response()->json([
                'message' => 'Cliente não encontrado'
            ], 404);
        }

        return response()->json($cliente);
    }

    /**
     * @OA\Put(
     *      path="/clientes/{id}",
     *      operationId="updateCliente",
     *      tags={"Clientes"},
     *      summary="Atualizar cliente",
     *      description="Atualiza os dados de um cliente existente",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID do cliente",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ClienteRequest")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Cliente atualizado com sucesso",
     *          @OA\JsonContent(ref="#/components/schemas/ClienteResponse")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Cliente não encontrado"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Erro de validação"
     *      )
     * )
     */
    public function update(ClienteRequest $request, int $id): JsonResponse
    {
        $cliente = $this->clienteService->buscarClientePorId($id);

        if (!$cliente) {
            return response()->json([
                'message' => 'Cliente não encontrado'
            ], 404);
        }

        try {
            $clienteAtualizado = $this->clienteService->atualizarCliente(
                $cliente, 
                $request->validated()
            );

            // Limpar cache se mudou o status
            if ($request->has('status')) {
                $this->clienteService->limparCacheStats();
            }

            return response()->json([
                'message' => 'Cliente atualizado com sucesso',
                'cliente' => $clienteAtualizado,
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar cliente', [
                'cliente_id' => $id,
                'error' => $e->getMessage(),
                'data' => $request->validated()
            ]);

            return response()->json([
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *      path="/clientes/{id}",
     *      operationId="deleteCliente",
     *      tags={"Clientes"},
     *      summary="Inativar cliente",
     *      description="Inativa um cliente (não remove do banco)",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID do cliente",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Cliente inativado com sucesso",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Cliente inativado com sucesso")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Cliente não encontrado"
     *      )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $cliente = $this->clienteService->buscarClientePorId($id);

        if (!$cliente) {
            return response()->json([
                'message' => 'Cliente não encontrado'
            ], 404);
        }

        try {
            $this->clienteService->inativarCliente($cliente);

            // Limpar cache das estatísticas
            $this->clienteService->limparCacheStats();

            return response()->json([
                'message' => 'Cliente inativado com sucesso'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao inativar cliente', [
                'cliente_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }
}