@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Detalhes da Consulta</div>

                <div class="card-body">
                    <div class="mb-3">
                        <strong>Paciente:</strong> {{ $appointment->patient->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Profissional:</strong> {{ $appointment->responsible->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Data e Hora:</strong> {{ \Carbon\Carbon::parse($appointment->starts_at)->format('d/m/Y H:i') }}
                    </div>
                    <div class="mb-3">
                        <strong>Duração:</strong> {{ $appointment->duration_min }} minutos
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong>
                        @if($appointment->status === 'scheduled')
                        <span class="badge bg-primary">Agendado</span>
                        @elseif($appointment->status === 'done')
                        <span class="badge bg-success">Realizado</span>
                        @else
                        <span class="badge bg-danger">Cancelado</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <strong>Observações:</strong>
                        <p class="text-muted">{{ $appointment->notes ?: 'Nenhuma observação.' }}</p>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Voltar</a>
                        <div>
                            <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning">Editar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection