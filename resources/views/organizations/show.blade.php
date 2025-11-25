@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Gerenciar Membros - {{ $organization->name }}
                </div>

                <div class="card-body">

                    <!-- Mensagem de Sucesso -->
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- Formulário de Adicionar (Só aparece se tiver permissão) -->
                    @can('manageMembers', $organization)
                    <form action="{{ route('organizations.members.invite', $organization->id) }}" method="POST" class="row g-3 mb-4 border-bottom pb-4">
                        @csrf
                        <div class="col-auto">
                            <label for="email" class="visually-hidden">Email do Usuário</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="email@exemplo.com" required>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary mb-3">Adicionar Staff</button>
                        </div>

                        @error('email')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </form>
                    @endcan

                    <!-- Lista de Membros -->
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Papel</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($organization->members as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email }}</td>
                                <td>
                                    @if($member->pivot->role === 'owner')
                                    <span class="badge bg-primary">Dono</span>
                                    @else
                                    <span class="badge bg-secondary">Staff</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <!-- Botão de Remover (Só aparece se tiver permissão e NÃO for o dono) -->
                                    @can('manageMembers', $organization)
                                    @if($member->pivot->role !== 'owner')
                                    <form action="{{ route('organizations.members.remove', [$organization->id, $member->id]) }}" method="POST" onsubmit="return confirm('Remover este membro da equipe?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Remover</button>
                                    </form>
                                    @else
                                    <small class="text-muted">--</small>
                                    @endif
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3">
                        <a href="{{ route('organizations.index') }}" class="btn btn-secondary">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection