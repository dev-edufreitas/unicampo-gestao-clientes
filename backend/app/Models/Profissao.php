<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Profissao",
 *     required={"nome_profissao"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nome_profissao", type="string", example="Engenheiro de Software"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Profissao extends Model
{
    use HasFactory;
    
    protected $table    = 'profissoes';
    protected $fillable = ['nome_profissao'];
    
    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'id_profissao');
    }
}