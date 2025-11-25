@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0">Dashboard</h4>
            </div>
            <div class="card-body">
                <h5 class="card-title">Bem-vindo(a), {{ Auth::user()->name }}!</h5>

                <hr>

                {{-- Lógica para verificar se existe uma clínica ativa na sessão --}}
                @php
                $activeOrg = Auth::user()->active_organization;
                @endphp

                @if($activeOrg)
                {{-- CENÁRIO 1: Usuário selecionou uma clínica --}}
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    Você está gerenciando a clínica: <strong>{{ $activeOrg->name }}</strong>
                </div>

                <p class="card-text">O que você deseja fazer agora?</p>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('patients.index') }}" class="btn btn-outline-primary">
                        Gerenciar Pacientes
                    </a>
                    <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary">
                        Agenda de Consultas
                    </a>
                    <a href="{{ route('organizations.show', $activeOrg->id) }}" class="btn btn-outline-secondary">
                        Ver Membros da Equipe
                    </a>
                </div>

                @else
                {{-- CENÁRIO 2: Nenhuma clínica selecionada --}}
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Nenhuma clínica selecionada no momento.
                </div>

                <p class="card-text">
                    Para acessar pacientes e consultas, você precisa primeiro selecionar ou criar uma clínica.
                </p>

                <div class="d-flex gap-2">
                    <a href="{{ route('organizations.index') }}" class="btn btn-primary">
                        Ver Minhas Clínicas
                    </a>
                    <a href="{{ route('organizations.create') }}" class="btn btn-success">
                        Criar Nova Clínica
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection