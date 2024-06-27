@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Vendas</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-sm-6">
            <a class="btn btn-success" href="{{ route('venda.create') }}">Realizar venda</a>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Relatório de Vendas
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('relatorio') }}">Imprimir</a></li>
                        <li><a class="dropdown-item" href="{{ route('relatorio', 'detalhado') }}">Imprimir Detalhado</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-6">
                <form action="{{ route('vendas') }}" method="GET" class="form-inline">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Pesquisar" value="{{ request()->get('search') }}">
                        <button type="submit" class="btn btn-outline-primary">Buscar</button>
                        <button type="button" class="btn btn-outline-danger" onclick="limparPesquisa()">X</button>

                    </div>
                </form>
            </div>

            <div class="col-sm-12 mt-2">
                <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Venda</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Data da Venda</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                @foreach($vendas as $venda)
                    <tr>
                        <td>{{ $venda->id }}</td>
                        <td>{{ $venda->cliente ? $venda->cliente->nome : 'Não informado' }}</td>
                        <td>R$ {{ number_format($venda->total, 2, ',', '.') }}</td>
                        <td>{{ $venda->formatted_data_venda }}</td>
                        <td>
                            <a href="{{ route('venda.show', $venda->id) }}" class="btn btn-sm btn-primary">Ver Detalhes</a>
                            <a href="{{ route('venda.edit', $venda->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('venda.destroy', $venda->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja deletar esta venda?')">Deletar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <script>
        function limparPesquisa() {
            document.querySelector('input[name="search"]').value = '';
            document.querySelector('form').submit();
        }
    </script>
@endsection

