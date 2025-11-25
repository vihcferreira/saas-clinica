@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Agenda de Consultas</h2>
                <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                    Nova Consulta
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    @if($appointments->isEmpty())
                    <p class="text-muted">Nenhuma consulta agendada.</p>
                    @else
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Data/Hora</th>
                                <th>Paciente</th>
                                <th>Responsável</th>
                                <th>Status</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($appointment->starts_at)->format('d/m/Y H:i') }}</td>
                                <td>{{ $appointment->patient->name }}</td>
                                <td>{{ $appointment->responsible->name }}</td>
                                <td>
                                    @if($appointment->status === 'scheduled')
                                    <span class="badge bg-primary">Agendado</span>
                                    @elseif($appointment->status === 'done')
                                    <span class="badge bg-success">Realizado</span>
                                    @else
                                    <span class="badge bg-danger">Cancelado</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-info text-white">
                                        Detalhes
                                    </a>
                                    <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-sm btn-warning">
                                        Editar
                                    </a>
                                    <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja cancelar/remover esta consulta?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                    </form>
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