<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Endereco",
 *     required={"endereco", "numero", "bairro", "cidade", "uf"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="endereco", type="string", example="Rua das Flores"),
 *     @OA\Property(property="numero", type="string", example="123"),
 *     @OA\Property(property="bairro", type="string", example="Centro"),
 *     @OA\Property(property="complemento", type="string", example="Apto 101"),
 *     @OA\Property(property="cidade", type="string", example="Maringá"),
 *     @OA\Property(property="uf", type="string", example="PR"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Endereco extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'endereco',
        'numero',
        'bairro',
        'complemento',
        'cidade',
        'uf'
    ];
    
    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'id_endereco');
    }
}