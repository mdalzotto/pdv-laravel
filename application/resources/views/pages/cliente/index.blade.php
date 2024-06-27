@extends('layouts.app')

@section('content')
    <div class="container">
    <h1 class="">Clientes</h1>

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
                <a class="btn btn-success" href="{{ route('cliente.create') }}">Cadastrar cliente</a>
            </div>

            <div class="col-sm-12 mt-2">
                <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th scope="col" width="80%">Nome</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                    <tbody>
                    @foreach($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->nome }}</td>
                            <td>
                                <a href="{{ route('cliente.edit', $cliente->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('cliente.destroy', $cliente->id) }}" method="POST" style="display:inline-block;">
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
