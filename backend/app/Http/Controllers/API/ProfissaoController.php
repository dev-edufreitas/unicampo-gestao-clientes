<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Profissao;

class ProfissaoController extends Controller
{
    public function index()
    {
        return response()->json(Profissao::all());
    }
    
    public function show($id)
    {
        $profissao = Profissao::find($id);
        
        if (!$profissao) {
            return response()->json(['error' => 'Profissão não encontrada'], 404);
        }
        
        return response()->json($profissao);
    }
}
