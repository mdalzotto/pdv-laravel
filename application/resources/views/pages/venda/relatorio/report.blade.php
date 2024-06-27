<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Vendas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 3px 0px 8px 2px;
        }
        th {
            background-color: #f2f2f2;
        }
        .item-venda {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<h1>Relatório de Vendas</h1>

<table>
    <thead>
    <tr>
        <th colspan="2">Venda</th>
        <th>Cliente</th>
        <th>Data da Venda</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($vendas as $venda)
        <tr>
            <th colspan="2">{{ $venda->id }}</th>
            <td>{{ $venda->cliente ? $venda->cliente->nome : "Cliente não informado" }}</td>
            <td>{{ $venda->formatted_data_venda }}</td>
            <td><b>R$ {{ number_format($venda->total, 2, ',', '.') }}</b></td>
        </tr>
        @if ($showItems)
            <tr class="">
                <td colspan="5">
                    <table>
                        <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Preço Unitário</th>
                            <th>Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($venda->itens as $item)
                            <tr>
                                <td>{{ $item->produto->nome }}</td>
                                <td>{{ $item->quantidade }}</td>
                                <td>R$ {{ number_format($item->valor, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($item->quantidade * $item->valor, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
</body>
</html>
