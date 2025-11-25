@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Pacientes</h2>
                <a href="{{ route('patients.create') }}" class="btn btn-primary">
                    Novo Paciente
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    @if($patients->isEmpty())
                    <p class="text-muted">Nenhum paciente cadastrado nesta clínica.</p>
                    @else
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patients as $patient)
                            <tr>
                                <td>{{ $patient->name }}</td>
                                <td>{{ $patient->email }}</td>
                                <td>{{ $patient->phone }}</td>
                                <td class="text-end">
                                    <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-sm btn-info text-white">
                                        Detalhes
                                    </a>
                                    <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-warning">
                                        Editar
                                    </a>
                                    <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este paciente?');">
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