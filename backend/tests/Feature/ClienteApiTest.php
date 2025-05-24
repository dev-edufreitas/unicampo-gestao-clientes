<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Cliente;
use App\Models\Endereco;
use App\Models\Profissao;

class ClienteApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa a listagem de clientes.
     */
    public function test_listar_clientes(): void
    {
        $profissao = Profissao::factory()->create();
        $endereco  = Endereco::factory()->create();

        for ($i = 1; $i <= 3; $i++) {
            Cliente::create([
                'nome'            => "Cliente Teste {$i}",
                'data_nascimento' => '1990-01-01',
                'tipo_pessoa'     => 'fisica',
                'documento'       => "1234567890{$i}",
                'email'           => "teste{$i}@example.com",
                'telefone'        => "(44) 99999-999{$i}",
                'endereco'        => 'Rua Teste',
                'numero'          => '100',
                'bairro'          => 'Centro',
                'cidade'          => 'Maringá',
                'uf'              => 'PR',
                'id_profissao'    => $profissao->id,
                'id_endereco'     => $endereco->id,
                'status'          => 'ativo',
            ]);
        }

        $response = $this->getJson('/api/v1/clientes');

        $response->assertOk()
                 ->assertJsonCount(3);
    }

    /**
     * Testa a criação de um cliente válido.
     */
    public function test_criar_cliente_valido(): void
    {
        $profissao = Profissao::factory()->create([
            'nome_profissao' => 'Engenheiro de Software',
        ]);

        $clienteData = [
            'nome'            => 'Italo Cesar Napoli',
            'data_nascimento' => '1990-01-01',
            'tipo_pessoa'     => 'fisica',
            'documento'       => '52998224725',
            'email'           => 'italo@unicampo.coop.br',
            'telefone'        => '(44) 99999-9999',
            'endereco'        => 'Rua Teste',
            'numero'          => '123',
            'bairro'          => 'Centro',
            'cidade'          => 'Maringá',
            'uf'              => 'PR',
            'id_profissao'    => $profissao->id,
        ];

        $response = $this->postJson('/api/v1/clientes', $clienteData);

        $response->assertCreated()
                 ->assertJsonPath('cliente.nome', $clienteData['nome']);
    }

    /**
     * Testa a edição de um cliente existente.
     */
    public function test_editar_cliente(): void
    {
        $profissao = Profissao::factory()->create();

        $clienteData = [
            'nome'            => 'Italo Cesar Napoli',
            'data_nascimento' => '1980-01-01',
            'tipo_pessoa'     => 'fisica',
            'documento'       => '52998224725',
            'email'           => 'italo@unicampo.coop.br',
            'telefone'        => '(44) 90000-0000',
            'endereco'        => 'Rua Inicial',
            'numero'          => '100',
            'bairro'          => 'Centro',
            'cidade'          => 'Maringá',
            'uf'              => 'PR',
            'id_profissao'    => $profissao->id,
        ];

        $createResponse = $this->postJson('/api/v1/clientes', $clienteData);
        $createResponse->assertCreated();
        $clienteId = $createResponse->json('cliente.id');

        $updateData = [
            'nome'            => 'Italo Cesar',
            'data_nascimento' => '1985-05-05',
            'tipo_pessoa'     => 'fisica',
            'documento'       => '52998224725',
            'email'           => 'italo.ti@unicampo.coop.br',
            'telefone'        => '(44) 98888-8888',
            'endereco'        => 'Rua Atualizada',
            'numero'          => '456',
            'bairro'          => 'Jardim',
            'cidade'          => 'Curitiba',
            'uf'              => 'PR',
            'id_profissao'    => $profissao->id,
        ];

        $response = $this->putJson("/api/v1/clientes/{$clienteId}", $updateData);

        $response->assertOk()
                 ->assertJsonPath('cliente.nome', $updateData['nome'])
                 ->assertJsonPath('cliente.email', $updateData['email']);
    }

    /**
     * Testa a inativação de um cliente.
     */
    public function test_inativar_cliente(): void
    {
        $profissao = Profissao::factory()->create();

        $clienteData = [
            'nome'            => 'Para Inativar',
            'data_nascimento' => '1990-06-06',
            'tipo_pessoa'     => 'fisica',
            'documento'       => '52998224725',
            'email'           => 'inativar@example.com',
            'telefone'        => '(44) 91111-1111',
            'endereco'        => 'Rua X',
            'numero'          => '789',
            'bairro'          => 'Centro',
            'cidade'          => 'Maringá',
            'uf'              => 'PR',
            'id_profissao'    => $profissao->id,
        ];

        $createResponse = $this->postJson('/api/v1/clientes', $clienteData);

        $createResponse->assertCreated();

        $clienteId  = $createResponse->json('cliente.id');
        $updateData = array_merge($clienteData, ['status' => 'inativo']);
        $response   = $this->putJson("/api/v1/clientes/{$clienteId}", $updateData);

        $response->assertOk()
                 ->assertJsonPath('cliente.status', 'inativo');
    }
}
