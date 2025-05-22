<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Endereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnderecoController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'endereco'    => 'required|string|max:255',
            'numero'      => 'required|string|max:20',
            'bairro'      => 'required|string|max:255',
            'complemento' => 'nullable|string|max:255',
            'cidade'      => 'required|string|max:255',
            'uf'          => 'required|string|size:2',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $endereco = Endereco::create($request->all());
        
        return response()->json($endereco, 201);
    }
    
    public function show($id)
    {
        $endereco = Endereco::find($id);
        
        if (!$endereco) {
            return response()->json(['error' => 'Endereço não encontrado'], 404);
        }
        
        return response()->json($endereco);
    }
}