<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ItemVenda;
use App\Models\Produto;
use App\Models\Venda;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class VendaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Venda::query();

            if ($request->filled('search')) {
                $search = $request->search;

                $query->orWhere('id', 'LIKE', "%$search%");
                $query->orWhereHas('cliente', function ($query) use ($search) {
                    $query->where('nome', 'LIKE', "%$search%");
                });
                $query->orWhere('total', '=', str_replace(',', '.', $search));
            }

            $vendas = $query->orderBy('created_at', 'desc')->get();
            return view('pages.venda.index', compact('vendas'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao listar vendas: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $venda = Venda::with(['cliente', 'itens.produto'])->findOrFail($id);
            return view('pages.venda.detalhes', compact('venda'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao exibir detalhes da venda: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $clientes = Cliente::all();
            $produtos = Produto::all();

            return view('pages.venda.form', compact('clientes', 'produtos'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao exibir formulário de criação de venda: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $venda = new Venda();
            $venda->cliente_id = $request->cliente_id;
            $venda->data_venda = now();
            $venda->save();

            $total = 0;

            $produtos = json_decode($request->produtos, true);

            foreach ($produtos as $produto) {
                $produtoModel = Produto::findOrFail($produto['produto_id']);

                if ($produtoModel->estoque < $produto['quantidade']) {
                    return redirect()->back()->withErrors(['Estoque insuficiente para o produto: ' . $produtoModel->nome]);
                }

                $produtoModel->estoque -= $produto['quantidade'];
                $produtoModel->save();

                $itemVenda = new ItemVenda();
                $itemVenda->venda_id = $venda->id;
                $itemVenda->produto_id = $produto['produto_id'];
                $itemVenda->quantidade = $produto['quantidade'];
                $itemVenda->valor = $produtoModel->preco * $produto['quantidade'];
                $itemVenda->save();

                $total += $itemVenda->valor;
            }

            $venda->total = $total;
            $venda->save();

            DB::commit();

            return redirect()->route('venda.create')->with('success', 'Venda realizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao criar venda: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $venda = Venda::with('itens.produto')->findOrFail($id);
            $clientes = Cliente::all();
            $produtos = Produto::all();

            $produtosJson = $venda->itens->map(function($item) {
                return [
                    'produto_id' => $item->produto_id,
                    'nome' => $item->produto->nome,
                    'quantidade' => $item->quantidade,
                    'preco' => $item->valor / $item->quantidade,
                    'total' => $item->valor
                ];
            })->toArray();

            return view('pages.venda.edit', compact('venda', 'clientes', 'produtos', 'produtosJson'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao exibir formulário de edição de venda: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $venda = Venda::findOrFail($id);
            $venda->cliente_id = $request->cliente_id;
            $venda->data_venda = now();

            foreach ($venda->itens as $item) {
                $produto = Produto::find($item->produto_id);
                $produto->estoque += $item->quantidade;
                $produto->save();
            }

            $venda->itens()->delete();

            $total = 0;
            $produtos = json_decode($request->produtos, true);

            foreach ($produtos as $produto) {
                $produtoModel = Produto::findOrFail($produto['produto_id']);

                if ($produtoModel->estoque < $produto['quantidade']) {
                    return redirect()->back()->withErrors(['Estoque insuficiente para o produto: ' . $produtoModel->nome]);
                }

                $produtoModel->estoque -= $produto['quantidade'];
                $produtoModel->save();

                $itemVenda = new ItemVenda();
                $itemVenda->venda_id = $venda->id;
                $itemVenda->produto_id = $produto['produto_id'];
                $itemVenda->quantidade = $produto['quantidade'];
                $itemVenda->valor = $produtoModel->preco * $produto['quantidade'];
                $itemVenda->save();

                $total += $itemVenda->valor;
            }

            $venda->total = $total;
            $venda->save();

            DB::commit();

            return redirect()->route('vendas')->with('success', 'Venda atualizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao atualizar venda: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $venda = Venda::findOrFail($id);

            foreach ($venda->itens as $item) {
                $produto = Produto::find($item->produto_id);
                $produto->estoque += $item->quantidade;
                $produto->save();
            }

            $venda->delete();

            DB::commit();

            return redirect()->route('vendas')->with('success', 'Venda excluída com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao excluir venda: ' . $e->getMessage());
        }
    }

    public function gerarPDF($detalhado = null)
    {
        try {
            $vendas = Venda::all();

            $showItems = !is_null($detalhado);

            $pdf = new Dompdf();
            $pdf->loadHtml(View::make('pages.venda.relatorio.report', compact('vendas', 'showItems'))->render());
            $pdf->render();

            return $pdf->stream('relatorio_vendas.pdf');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao gerar PDF de vendas: ' . $e->getMessage());
        }
    }
}
