@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $produto->id ? 'Editar Produto' : 'Criar Produto' }}</h1>

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

        <form action="{{ $produto->id ? route('produto.update', $produto->id) : route('produto.store') }}" method="POST">
            @csrf
            @if($produto->id)
                @method('PUT')
            @endif

            <div class="form-group">
                <label for="nome">Nome <i class="text-danger">*</i></label>
                <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $produto->nome) }}" required>
            </div>

            <div class="form-group">
                <label for="ean">Cod Barras <i class="text-danger">*</i></label>
                <input type="number" class="form-control" id="ean" name="ean" value="{{ old('ean', $produto->ean) }}" required>
            </div>

            <div class="form-group">
                <label for="estoque">Estoque <i class="text-danger">*</i></label>
                <input type="number" class="form-control" id="estoque" name="estoque" value="{{ old('estoque', $produto->estoque) }}" required>
            </div>

            <div class="form-group">
                <label for="preco">Preço <i class="text-danger">*</i></label>
                <input type="number" class="form-control" id="preco" name="preco" step="0.01" value="{{ old('preco', $produto->preco) }}" required>
            </div>

            <div class="col-sm-12 mt-2">
                <button type="submit" class="btn btn-success ">{{ $produto->id ? 'Atualizar' : 'Criar' }}</button>
                <a  href="{{ route('produtos') }}" class="btn btn-danger">Voltar</a>
            </div>
        </form>
    </div>
@endsection
