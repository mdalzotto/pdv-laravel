<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\VendaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes');

Route::get('/clientes/criar', [ClienteController::class, 'create'])->name('cliente.create');
Route::post('/clientes/store', [ClienteController::class, 'store'])->name('cliente.store');

Route::get('/clientes/{id}/editar', [ClienteController::class, 'edit'])->name('cliente.edit');
Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('cliente.update');
Route::delete('/clientes/destroy/{id}', [ClienteController::class, 'destroy'])->name('cliente.destroy');


Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos');

Route::get('/produtos/criar', [ProdutoController::class, 'create'])->name('produto.create');
Route::post('/produtos/store', [ProdutoController::class, 'store'])->name('produto.store');

Route::get('/produtos/{id}/editar', [ProdutoController::class, 'edit'])->name('produto.edit');
Route::put('/produtos/{id}', [ProdutoController::class, 'update'])->name('produto.update');
Route::delete('/produtos/destroy/{id}', [ProdutoController::class, 'destroy'])->name('produto.destroy');



Route::get('/vendas', [VendaController::class, 'index'])->name('vendas');
Route::get('/', [VendaController::class, 'create'])->name('venda.create');
Route::post('/vendas/store', [VendaController::class, 'store'])->name('venda.store');
Route::delete('/vendas/destroy/{id}', [VendaController::class, 'destroy'])->name('venda.destroy');

Route::get('/vendas/{id}/edit', [VendaController::class, 'edit'])->name('venda.edit');
Route::put('/vendas/{id}', [VendaController::class, 'update'])->name('venda.update');
Route::get('/vendas/{id}', [VendaController::class, 'show'])->name('venda.show');


Route::get('/relatorio/{detalhado?}', [VendaController::class, 'gerarPDF'])->name('relatorio');


