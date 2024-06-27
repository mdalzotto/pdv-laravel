@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $cliente->id ? 'Editar Cliente' : 'Criar Cliente' }}</h1>

        <form action="{{ $cliente->id ? route('cliente.update', $cliente->id) : route('cliente.store') }}" method="POST">
            @csrf
            @if($cliente->id)
                @method('PUT')
            @endif

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="nome">Nome <i class="text-danger">*</i></label>
                        <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $cliente->nome) }}" required>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 mt-2">
                <button type="submit" class="btn btn-success ">{{ $cliente->id ? 'Atualizar' : 'Criar' }}</button>
                <a  href="{{ route('clientes') }}" class="btn btn-danger">Voltar</a>
            </div>
        </form>
    </div>
@endsection
