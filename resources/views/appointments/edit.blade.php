@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Editar Consulta</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('appointments.update', $appointment->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Paciente</label>
                            <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="responsible_user_id" class="form-label">Profissional Responsável</label>
                            <select name="responsible_user_id" id="responsible_user_id" class="form-select @error('responsible_user_id') is-invalid @enderror" required>
                                @foreach($staffMembers as $staff)
                                <option value="{{ $staff->id }}" {{ old('responsible_user_id', $appointment->responsible_user_id) == $staff->id ? 'selected' : '' }}>
                                    {{ $staff->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('responsible_user_id')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="starts_at" class="form-label">Data e Hora</label>
                                <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror"
                                    id="starts_at" name="starts_at" value="{{ old('starts_at', $appointment->starts_at) }}" required>
                                @error('starts_at')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="duration_min" class="form-label">Duração (minutos)</label>
                                <input type="number" class="form-control @error('duration_min') is-invalid @enderror"
                                    id="duration_min" name="duration_min" value="{{ old('duration_min', $appointment->duration_min) }}" required min="5">
                                @error('duration_min')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="scheduled" {{ old('status', $appointment->status) == 'scheduled' ? 'selected' : '' }}>Agendado</option>
                                <option value="done" {{ old('status', $appointment->status) == 'done' ? 'selected' : '' }}>Realizado</option>
                                <option value="canceled" {{ old('status', $appointment->status) == 'canceled' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('status')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Observações</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                            @error('notes')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Atualizar Consulta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection