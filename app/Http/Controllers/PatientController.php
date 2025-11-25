<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Support\Facades\Gate;

class PatientController extends Controller
{
    /**
     * Helper privado para pegar o ID da clínica e garantir que existe.
     */
    private function getActiveOrgId()
    {
        $id = session('active_organization_id');
        if (!$id) {
            abort(redirect()->route('organizations.index')->with('error', 'Selecione uma clínica para continuar.'));
        }
        return $id;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orgId = $this->getActiveOrgId();
        if (!$orgId) {
            return redirect()->route('organizations.index')->with('error', 'Por favor, selecione uma clínica primeiro.');
        }

        $patients = Patient::where('organization_id', $orgId)->get();

        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->getActiveOrgId();

        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientRequest $request)
    {
        $orgId = $this->getActiveOrgId();

        Patient::create(array_merge($request->validated(), [
            'organization_id' => $orgId
        ]));

        return redirect()->route('patients.index')
            ->with('success', 'Paciente cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        $orgId = $this->getActiveOrgId();

        // SEGURANÇA DE ESCOPO: Verifica se o paciente pertence à clínica ativa
        if ($patient->organization_id != $orgId) {
            abort(403, 'Acesso negado a este paciente.');
        }

        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        $orgId = $this->getActiveOrgId();

        // SEGURANÇA DE ESCOPO
        if ($patient->organization_id != $orgId) {
            abort(403, 'Acesso negado a este paciente.');
        }

        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $orgId = $this->getActiveOrgId();

        if ($patient->organization_id != $orgId) {
            abort(403);
        }

        // Atualiza os dados
        $patient->update($request->validated());

        return redirect()->route('patients.index')
                         ->with('success', 'Dados do paciente atualizados.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $orgId = $this->getActiveOrgId();

        if ($patient->organization_id != $orgId) {
            abort(403);
        }
        
        // Aqui entrará a Policy "delete" depois, mas o escopo já protege o acesso cruzado
        $patient->delete();

        return redirect()->route('patients.index')
                         ->with('success', 'Paciente removido.');
    }
}
