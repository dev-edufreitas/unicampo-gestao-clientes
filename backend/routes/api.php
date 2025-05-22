<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProfissaoController;
use App\Http\Controllers\API\EnderecoController;
use App\Http\Controllers\API\ClienteController;

Route::prefix('v1')->group(function () {
    // Rotas para Profissões
    Route::get('/profissoes', [ProfissaoController::class, 'index']);
    Route::get('/profissoes/{id}', [ProfissaoController::class, 'show']);
    
    // Rotas para Endereços
    Route::post('/enderecos', [EnderecoController::class, 'store']);
    Route::get('/enderecos/{id}', [EnderecoController::class, 'show']);
    
    // Rotas para Clientes
    Route::get('/clientes', [ClienteController::class, 'index']);
    Route::get('/clientes/stats', [ClienteController::class, 'getStats']); 
    Route::post('/clientes', [ClienteController::class, 'store']);
    Route::get('/clientes/{id}', [ClienteController::class, 'show']);
    Route::put('/clientes/{id}', [ClienteController::class, 'update']);
    Route::delete('/clientes/{id}', [ClienteController::class, 'destroy']);
});