@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="">Produtos</h1>

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
            <div class="col-sm-12 ">
                <a class="btn btn-success" href="{{ route('produto.create') }}">Cadastrar produto</a>
            </div>

            <div class="col-sm-12 mt-2">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th scope="col" width="35%">Nome</th>
                        <th scope="col" width="25%">Cod. Barras</th>
                        <th scope="col" width="10%">Valor</th>
                        <th scope="col" width="10%">Estoque</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($produtos as $produto)
                        <tr>
                            <td>{{ $produto->nome }}</td>
                            <td>{{ $produto->ean }}</td>
                            <td>{{ number_format( $produto->preco, 2, ',', '.') }}</td>
                            <td>{{ $produto->estoque }}</td>
                            <td>
                                <a href="{{ route('produto.edit', $produto->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('produto.destroy', $produto->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

