@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Detalhes do Paciente</div>

                <div class="card-body">
                    <div class="mb-3">
                        <strong>Nome:</strong> {{ $patient->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> {{ $patient->email }}
                    </div>
                    <div class="mb-3">
                        <strong>Telefone:</strong> {{ $patient->phone }}
                    </div>
                    <div class="mb-3">
                        <strong>Data de Nascimento:</strong> {{ $patient->birthdate ? \Carbon\Carbon::parse($patient->birthdate)->format('d/m/Y') : 'Não informado' }}
                    </div>
                    <div class="mb-3">
                        <strong>Cadastrado em:</strong> {{ $patient->created_at->format('d/m/Y H:i') }}
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('patients.index') }}" class="btn btn-secondary">Voltar</a>
                        <div>
                            <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning">Editar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection