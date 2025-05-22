<?php

namespace App\Services;

use App\Models\Cliente;
use App\Models\Endereco;
use App\Utils\DocumentoFormatter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ClienteService
{
    /**
     * Buscar clientes com filtros
     */
    public function buscarClientes(array $filtros = []): Collection
    {
        $query = Cliente::with(['endereco', 'profissao']);

        if (!empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        }

        if (!empty($filtros['nome'])) {
            $query->where('nome', 'like', '%' . $filtros['nome'] . '%');
        }

        $orderDirection = ($filtros['order_desc'] ?? false) ? 'desc' : 'asc';
        $query->orderBy('created_at', $orderDirection);

        return $query->get();
    }

    /**
     * Buscar cliente por ID
     */
    public function buscarClientePorId(int $id): ?Cliente
    {
        return Cliente::with(['endereco', 'profissao'])->find($id);
    }

    /**
     * Criar novo cliente
     */
    public function criarCliente(array $dados): Cliente
    {
        $endereco     = $this->criarEndereco($dados);
        $dadosCliente = $this->formatarDadosCliente($dados, $endereco->id);
        $cliente      = Cliente::create($dadosCliente);

        return $cliente->load(['endereco', 'profissao']);
    }

    /**
     * Atualizar cliente existente
     */
    public function atualizarCliente(Cliente $cliente, array $dados): Cliente
    {
        $this->atualizarEndereco($cliente->endereco, $dados);

        $dadosCliente = $this->formatarDadosCliente($dados, $cliente->id_endereco);

        $cliente->update($dadosCliente);

        return $cliente->fresh(['endereco', 'profissao']);
    }

    /**
     * Inativar cliente (soft delete)
     */
    public function inativarCliente(Cliente $cliente): bool
    {
        return $cliente->update(['status' => 'inativo']);
    }

    /**
     * Criar endereço
     */
    private function criarEndereco(array $dados): Endereco
    {
        return Endereco::create([
            'endereco'    => $dados['endereco'],
            'numero'      => $dados['numero'],
            'bairro'      => $dados['bairro'],
            'complemento' => $dados['complemento'] ?? null,
            'cidade'      => $dados['cidade'],
            'uf'          => $dados['uf'],
        ]);
    }

    /**
     * Atualizar endereço
     */
    private function atualizarEndereco(Endereco $endereco, array $dados): bool
    {
        return $endereco->update([
            'endereco'    => $dados['endereco'],
            'numero'      => $dados['numero'],
            'bairro'      => $dados['bairro'],
            'complemento' => $dados['complemento'] ?? null,
            'cidade'      => $dados['cidade'],
            'uf'          => $dados['uf'],
        ]);
    }

    /**
     * Obter estatísticas dos clientes (total, ativos, novos do mês)
     */
    public function obterEstatisticas(): array
    {
        return Cache::remember('cliente_stats', 300, function () {
            $total    = Cliente::count();
            $ativos   = Cliente::where('status', 'ativo')->count();
            $novosMes = Cliente::whereYear('created_at', now()->year)
                             ->whereMonth('created_at', now()->month)
                             ->count();
            
            return [
                'total_clientes'  => number_format($total, 0, '.', ','),
                'clientes_ativos' => number_format($ativos, 0, '.', ','),
                'novos_mes'       => number_format($novosMes, 0, '.', ','),
            ];
        });
    }

    /**
     * Limpar cache das estatísticas
     */
    public function limparCacheStats(): void
    {
        Cache::forget('cliente_stats');
    }

    /**
     * Formatar dados do cliente para criação/atualização
     */
    private function formatarDadosCliente(array $dados, int $idEndereco): array
    {
        return [
            'nome'            => $dados['nome'],
            'data_nascimento' => $dados['data_nascimento'],
            'tipo_pessoa'     => $dados['tipo_pessoa'],
            'documento'       => DocumentoFormatter::formatarDocumento(
                $dados['documento'], 
                $dados['tipo_pessoa']
            ),
            'email'        => $dados['email'],
            'telefone'     => DocumentoFormatter::formatarTelefone($dados['telefone']),
            'id_endereco'  => $idEndereco,
            'id_profissao' => $dados['id_profissao'],
            'status'       => $dados['status'] ?? 'ativo',
        ];
    }
}