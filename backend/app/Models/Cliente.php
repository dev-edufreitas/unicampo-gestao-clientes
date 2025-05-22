<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Cliente",
 *     required={"nome", "data_nascimento", "tipo_pessoa", "documento", "email", "telefone", "id_endereco", "id_profissao", "status"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nome", type="string", example="João Silva"),
 *     @OA\Property(property="data_nascimento", type="string", format="date", example="1990-01-01"),
 *     @OA\Property(property="tipo_pessoa", type="string", enum={"fisica", "juridica"}, example="fisica"),
 *     @OA\Property(property="documento", type="string", example="123.456.789-00"),
 *     @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
 *     @OA\Property(property="telefone", type="string", example="(44) 99999-9999"),
 *     @OA\Property(property="id_endereco", type="integer", example=1),
 *     @OA\Property(property="id_profissao", type="integer", example=1),
 *     @OA\Property(property="status", type="string", enum={"ativo", "inativo"}, example="ativo"),
 *     @OA\Property(property="endereco", ref="#/components/schemas/Endereco"),
 *     @OA\Property(property="profissao", ref="#/components/schemas/Profissao"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="ClienteRequest",
 *     required={"nome", "data_nascimento", "tipo_pessoa", "documento", "email", "telefone", "endereco", "numero", "bairro", "cidade", "uf", "id_profissao"},
 *     @OA\Property(property="nome", type="string", example="João Silva"),
 *     @OA\Property(property="data_nascimento", type="string", format="date", example="1990-01-01"),
 *     @OA\Property(property="tipo_pessoa", type="string", enum={"fisica", "juridica"}, example="fisica"),
 *     @OA\Property(property="documento", type="string", example="123.456.789-00"),
 *     @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
 *     @OA\Property(property="telefone", type="string", example="(44) 99999-9999"),
 *     @OA\Property(property="endereco", type="string", example="Rua das Flores"),
 *     @OA\Property(property="numero", type="string", example="123"),
 *     @OA\Property(property="bairro", type="string", example="Centro"),
 *     @OA\Property(property="complemento", type="string", example="Apto 101"),
 *     @OA\Property(property="cidade", type="string", example="Maringá"),
 *     @OA\Property(property="uf", type="string", example="PR"),
 *     @OA\Property(property="id_profissao", type="integer", example=1)
 * )
 * 
 * @OA\Schema(
 *     schema="ClienteResponse",
 *     @OA\Property(property="message", type="string", example="Cliente cadastrado com sucesso"),
 *     @OA\Property(property="cliente", ref="#/components/schemas/Cliente")
 * )
 */
class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'data_nascimento',
        'tipo_pessoa',
        'documento',
        'email',
        'telefone',
        'id_endereco',
        'id_profissao',
        'status'
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];
    
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }


    public function endereco()
    {
        return $this->belongsTo(Endereco::class, 'id_endereco');
    }

    public function profissao()
    {
        return $this->belongsTo(Profissao::class, 'id_profissao');
    }
}
