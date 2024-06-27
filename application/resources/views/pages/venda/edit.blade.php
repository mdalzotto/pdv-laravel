@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Frente de Caixa - Edição de Venda</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('venda.update', $venda->id) }}" method="POST" onsubmit="return validarEnvio()">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="cliente_id">Cliente</label>
                        <select class="form-control" id="cliente_id" name="cliente_id">
                            <option value="">Selecione um cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ $venda->cliente_id == $cliente->id ? 'selected' : '' }}>{{ $cliente->nome }}</option>
                            @endforeach
                        </select>
                        <br>
                    </div>

                    <div class="form-group">
                        <label for="produto">Produto</label>
                        <select class="form-control" id="produto">
                            <option value="">Selecione um produto</option>
                            @foreach($produtos as $produto)
                                <option value="{{ $produto->id }}" data-nome="{{ $produto->nome }}" data-preco="{{ $produto->preco }}" data-estoque="{{ $produto->estoque }}">{{ $produto->ean }} - {{ $produto->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="quantidade">Quantidade</label>
                        <input type="number" class="form-control" id="quantidade" min="1" value="1">
                    </div>

                    <button type="button" class="btn btn-primary mt-2" onclick="adicionarProduto()">Adicionar Produto</button>
                </div>

                <div class="col-sm-9">
                    <br>
                    <table class="table table-bordered" id="produtos-table">
                        <thead>
                        <tr>
                            <th width="50%">Nome</th>
                            <th width="10%">Quantidade</th>
                            <th width="15%">Preço Un.</th>
                            <th width="15%">Total</th>
                            <th width="10%"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($venda->itens as $item)
                            <tr>
                                <td>{{ $item->produto->nome }}</td>
                                <td>{{ $item->quantidade }}</td>
                                <td>{{ number_format($item->valor_unitario, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removerProduto('{{ $item->produto_id }}')">Remover</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-sm-12 text-end">
                            <h3 id="total-venda">{{ number_format($venda->total, 2, ',', '.') }}</h3>
                        </div>
                    </div>

                    <input type="hidden" name="produtos" id="produtos-hidden" value="{{ old('produtos', isset($produtosJson) ? json_encode($produtosJson) : '[]') }}">
                </div>
            </div>

            <div class="row fixed-bottom justify-content-end m-4">
                <div class="col-sm-12 text-end">
                    <button type="submit" class="btn btn-success">Salvar Alterações</button>
                    <a href="{{ route('vendas') }}" class="btn btn-danger">Cancelar</a>
                </div>
            </div>
        </form>
    </div>

    <script>
        let produtos = [];

        document.addEventListener('DOMContentLoaded', () => {
            produtos = JSON.parse(document.getElementById('produtos-hidden').value);
            renderizarProdutos();
        });

        function adicionarProduto() {
            const produtoSelect = document.getElementById('produto');
            const quantidadeInput = document.getElementById('quantidade');
            const produtoId = produtoSelect.value;
            const produtoNome = produtoSelect.options[produtoSelect.selectedIndex].dataset.nome;
            const produtoPreco = parseFloat(produtoSelect.options[produtoSelect.selectedIndex].dataset.preco);
            const produtoEstoque = parseInt(produtoSelect.options[produtoSelect.selectedIndex].dataset.estoque);
            const quantidade = parseInt(quantidadeInput.value);

            if (!produtoId) {
                alert('Selecione um produto.');
                return;
            }

            if (quantidade <= 0) {
                alert('A quantidade deve ser maior que zero.');
                return;
            }

            if (quantidade > produtoEstoque) {
                alert('Quantidade excede o estoque disponível.');
                return;
            }

            const produtoExistente = produtos.find(p => p.produto_id === produtoId);
            if (produtoExistente) {
                produtoExistente.quantidade += quantidade;
                produtoExistente.total = produtoExistente.quantidade * produtoPreco;
            } else {
                const totalProduto = quantidade * produtoPreco;
                produtos.push({
                    produto_id: produtoId,
                    nome: produtoNome,
                    quantidade: quantidade,
                    preco: produtoPreco,
                    total: totalProduto
                });
            }

            calcularTotalVenda();
            renderizarProdutos();
            quantidadeInput.value = '1';
            produtoSelect.value = '';
        }

        function calcularTotalVenda() {
            let totalVenda = produtos.reduce((total, produto) => total + parseFloat(produto.total), 0);
            document.getElementById('total-venda').innerText = totalVenda.toFixed(2);
        }

        function removerProduto(produtoId) {
            console.log('Produto ID a ser removido:', produtoId);
            console.log('Lista de produtos antes da remoção:', produtos);

            produtos = produtos.filter(p => p.produto_id.toString() !== produtoId.toString());

            console.log('Lista de produtos após a remoção:', produtos);

            calcularTotalVenda();
            renderizarProdutos();
        }

        function renderizarProdutos() {
            const produtosTableBody = document.getElementById('produtos-table').querySelector('tbody');
            produtosTableBody.innerHTML = '';

            produtos.forEach(produto => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${produto.nome}</td>
                    <td>${produto.quantidade}</td>
                    <td>${produto.preco.toFixed(2)}</td>
                    <td>${parseFloat(produto.total).toFixed(2)}</td>
                    <td><button type="button" class="btn btn-sm btn-danger" onclick="removerProduto('${produto.produto_id}')">Remover</button></td>
                `;
                produtosTableBody.appendChild(row);
            });

            document.getElementById('produtos-hidden').value = JSON.stringify(produtos);
            calcularTotalVenda();
        }

        // Validar o envio do formulário (verificar se há itens na lista de produtos)
        function validarEnvio() {
            if (produtos.length === 0) {
                alert('Adicione pelo menos um produto à venda.');
                return false;
            }
            return true;
        }
    </script>

@endsection
