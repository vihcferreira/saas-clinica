<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizations = Auth::user()->organizations;
        return view('organizations.index', compact('organizations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('organizations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrganizationRequest $request)
    {
        $organization = Organization::create([
            'name' => $request->name,
            'owner_id' => Auth::id(),
        ]);

        // Associa o criador como membro com o papel de 'owner'
        $organization->members()->attach(Auth::id(), ['role' => 'owner']);

        return redirect()->route('organizations.index')->with('success', 'Clínica criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        Gate::authorize('view', $organization);

        $organization->load('members');

        return view('organizations.show', compact('organization'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization)
    {
        Gate::authorize('update', $organization);

        return view('organizations.edit', compact('organization'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization)
    {
        Gate::authorize('update', $organization);

        $organization->update($request->validated());

        return redirect()->route('organizations.index')
            ->with('success', 'Clínica atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        Gate::authorize('delete', $organization);

        $organization->delete();

        return redirect()->route('organizations.index')
            ->with('success', 'Clínica excluída com sucesso!');
    }

    //MÉTODO PARA ENTRAR NA CLÍNICA (COLOCAR NA SESSAO)
    public function switch(Organization $organization)
    {
        if (!Auth::user()->organizations->contains($organization)) {
            abort(403);
        }

        // Salva na sessão
        session(['active_organization_id' => $organization->id]);
        session(['active_organization_name' => $organization->name]); // Opcional, útil para exibir no menu

        return redirect()->route('dashboard')
            ->with('success', "Você acessou a clínica: {$organization->name}");
    }

    public function inviteMember(Request $request, Organization $organization)
    {
        // 1. Autorização: Só o dono pode adicionar membros
        Gate::authorize('manageMembers', $organization);

        // 2. Validação
        $request->validate([
            'email' => 'required|email|exists:users,email', // O usuário DEVE existir no sistema
        ], [
            'email.exists' => 'Este usuário não foi encontrado no sistema.',
        ]);

        // 3. Encontrar o usuário
        $userToAdd = User::where('email', $request->email)->first();

        // 4. Verificar se já é membro para evitar duplicação
        if ($organization->members->contains($userToAdd)) {
            return back()->withErrors(['email' => 'Este usuário já é membro da clínica.']);
        }

        // 5. Adicionar à tabela pivot (organization_user) com papel 'staff'
        $organization->members()->attach($userToAdd->id, ['role' => 'staff']);

        return back()->with('success', 'Membro adicionado com sucesso!');
    }

    /**
     * Remove um membro da clínica.
     */
    public function removeMember(Organization $organization, User $user)
    {
        // 1. Autorização
        Gate::authorize('manageMembers', $organization);

        // 2. Proteção: Não permitir remover o próprio dono
        if ($organization->owner_id === $user->id) {
            return back()->with('error', 'O dono da clínica não pode ser removido.');
        }

        // 3. Remover da tabela pivot
        $organization->members()->detach($user->id);

        return back()->with('success', 'Membro removido com sucesso!');
    }
}
