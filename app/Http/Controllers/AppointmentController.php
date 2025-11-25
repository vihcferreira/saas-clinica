<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;

class AppointmentController extends Controller
{
    private function getActiveOrgId()
    {
        $id = session('active_organization_id');
        if (!$id) {
            abort(redirect()->route('organizations.index')->with('error', 'Selecione uma clínica.'));
        }
        return $id;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orgId = $this->getActiveOrgId();

        // ESCOPO: Consultas da clínica, trazendo dados do paciente e responsável para exibir na tabela
        $appointments = Appointment::with(['patient', 'responsible'])
            ->where('organization_id', $orgId)
            ->orderBy('starts_at', 'desc')
            ->get();

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orgId = $this->getActiveOrgId();

        // Carregar dados para os <select> do formulário
        // 1. Apenas pacientes desta clínica
        $patients = Patient::where('organization_id', $orgId)->get();

        // 2. Apenas usuários que são membros desta clínica (Staff/Owner)
        // Usamos o whereHas para filtrar pela relação many-to-many
        $staffMembers = User::whereHas('organizations', function ($query) use ($orgId) {
            $query->where('organizations.id', $orgId);
        })->get();

        return view('appointments.create', compact('patients', 'staffMembers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
         $orgId = $this->getActiveOrgId();

        // Cria a consulta forçando a organização
        Appointment::create(array_merge($request->validated(), [
            'organization_id' => $orgId
        ]));

        return redirect()->route('appointments.index')
                         ->with('success', 'Consulta agendada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        $orgId = $this->getActiveOrgId();

        // SEGURANÇA DE ESCOPO: Garante que a consulta é desta clínica
        if ($appointment->organization_id != $orgId) {
            abort(403);
        }

        // Carrega relacionamentos para exibir nomes na tela
        $appointment->load(['patient', 'responsible']);

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $orgId = $this->getActiveOrgId();

        // Validação de Escopo
        if ($appointment->organization_id != $orgId) {
            abort(403);
        }

        // Precisamos das listas novamente para editar
        $patients = Patient::where('organization_id', $orgId)->get();
        $staffMembers = User::whereHas('organizations', function($query) use ($orgId) {
            $query->where('organizations.id', $orgId);
        })->get();

        return view('appointments.edit', compact('appointment', 'patients', 'staffMembers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $orgId = $this->getActiveOrgId();

        if ($appointment->organization_id != $orgId) {
            abort(403);
        }

        $appointment->update($request->validated());

        return redirect()->route('appointments.index')
                         ->with('success', 'Consulta atualizada.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $orgId = $this->getActiveOrgId();

        if ($appointment->organization_id != $orgId) {
            abort(403);
        }

        $appointment->delete();

        return redirect()->route('appointments.index')
                         ->with('success', 'Consulta cancelada/removida.');
    }
}
