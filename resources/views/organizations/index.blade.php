@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <!-- Mensagem de Sucesso -->
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Minhas Clínicas</h2>
                <a href="{{ route('organizations.create') }}" class="btn btn-primary">
                    Nova Clínica
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    @if($organizations->isEmpty())
                    <p class="text-muted">Você ainda não participa de nenhuma clínica.</p>
                    @else
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Seu Papel</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($organizations as $org)
                            <tr>
                                <td>{{ $org->name }}</td>
                                <td>
                                    @if($org->pivot->role === 'owner')
                                    <span class="badge bg-primary">Dono (Owner)</span>
                                    @else
                                    <span class="badge bg-secondary">Equipe (Staff)</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <!-- Botão ACESSAR -->
                                    <form action="{{ route('organizations.switch', $org->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            Acessar Painel
                                        </button>
                                    </form>

                                    <!-- Botão MEMBROS -->
                                    <a href="{{ route('organizations.show', $org->id) }}" class="btn btn-sm btn-info text-white">
                                        Membros
                                    </a>

                                    <!-- Botão EDITAR (Restrito) -->
                                    @can('update', $org)
                                    <a href="{{ route('organizations.edit', $org->id) }}" class="btn btn-sm btn-warning">
                                        Editar
                                    </a>
                                    @endcan

                                    <!-- Botão EXCLUIR (Restrito) -->
                                    @can('delete', $org)
                                    <form action="{{ route('organizations.destroy', $org->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta clínica?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection