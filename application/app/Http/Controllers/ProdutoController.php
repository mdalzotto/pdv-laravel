<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    public function index()
    {
        try {
            $produtos = Produto::all();
            return view('pages.produto.index', compact('produtos'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao listar produtos: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('pages.produto.form', ['produto' => new Produto()]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao exibir formulário de criação: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $produto = new Produto($request->all());
            $produto->save();

            DB::commit();

            return redirect()->route('produtos')->with('success', 'Produto criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao criar produto: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $produto = Produto::findOrFail($id);
            return view('pages.produto.form', compact('produto'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao exibir formulário de edição: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $produto = Produto::findOrFail($id);
            $produto->update($request->all());

            DB::commit();

            return redirect()->route('produtos')->with('success', 'Produto atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao atualizar produto: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $produto = Produto::findOrFail($id);
            $produto->delete();

            DB::commit();

            return redirect()->route('produtos')->with('success', 'Produto excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao excluir produto: ' . $e->getMessage());
        }
    }
}
