@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detalhes da Venda</h1>

        <div class="card">
            <div class="card-header">
                Pedido #{{ $venda->id }}
            </div>
            <div class="card-body">
                <h5 class="card-title">Cliente: {{ $venda->cliente ? $venda->cliente->nome : "Não informado" }}</h5>
                <p class="card-text">Data da Venda: {{ $venda->formatted_data_venda }}</p>
                <p class="card-text">Total: R$ {{ number_format($venda->total, 2, ',', '.') }}</p>

                <h5>Itens do Pedido:</h5>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($venda->itens as $item)
                        <tr>
                            <td>{{ $item->produto->nome }}</td>
                            <td>{{ $item->quantidade }}</td>
                            <td>R$ {{ number_format($item->valor / $item->quantidade, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($item->valor, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3"> </td>
                        <td><b>R$ {{ number_format($venda->total, 2, ',', '.') }}</b></td>
                    </tr>
                    </tbody>
                </table>

                <a href="{{ route('vendas') }}" class="btn btn-danger">Voltar</a>
            </div>
        </div>
    </div>
@endsection
