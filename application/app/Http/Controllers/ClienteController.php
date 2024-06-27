<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index()
    {
        try {
            $clientes = Cliente::all();
            return view('pages.cliente.index', compact('clientes'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao carregar clientes: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('pages.cliente.form', ['cliente' => new Cliente()]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $cliente = new Cliente($request->all());
            $cliente->save();

            DB::commit();

            return redirect()->route('clientes')->with('success', 'Criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->withInput()->with('error', 'Erro ao criar cliente: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            return view('pages.cliente.form', compact('cliente'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao editar cliente: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->update($request->all());

            DB::commit();

            return redirect()->route('clientes')->with('success', 'Atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->withInput()->with('error', 'Erro ao atualizar cliente: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();

            DB::commit();

            return redirect()->route('clientes')->with('success', 'Excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Erro ao excluir cliente: ' . $e->getMessage());
        }
    }
}
