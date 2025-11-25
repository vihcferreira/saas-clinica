<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 1. Criar Usuário DONO (Owner)
        // Login: dono@clinica.com / Senha: password
        $owner = User::create([
            'name' => 'Carlos Dono',
            'email' => 'dono@clinica.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Criar Usuário STAFF (Equipe)
        // Login: staff@clinica.com / Senha: password
        $staff = User::create([
            'name' => 'Ana Recepcionista',
            'email' => 'staff@clinica.com',
            'password' => Hash::make('password'),
        ]);

        // 3. Criar Usuário STAFF Médico
        // Login: medico@clinica.com / Senha: password
        $medico = User::create([
            'name' => 'Dr. Roberto',
            'email' => 'medico@clinica.com',
            'password' => Hash::make('password'),
        ]);

        // 4. Criar um Usuário INTRUSO (Para testar acesso negado)
        // Login: intruso@outra.com / Senha: password
        $intruso = User::create([
            'name' => 'João Intruso',
            'email' => 'intruso@outra.com',
            'password' => Hash::make('password'),
        ]);

        // -------------------------------------------------------

        // 5. Criar a CLÍNICA 1 (Clínica Central)
        $clinica1 = Organization::create([
            'name' => 'Clínica Central',
            'owner_id' => $owner->id, // O Carlos é o dono proprietário
        ]);

        // IMPORTANTE: Preencher a tabela Pivô (organization_user)

        // Adiciona o Dono na lista de membros com papel 'owner'
        $clinica1->members()->attach($owner->id, ['role' => 'owner']);

        // Adiciona a Staff na lista de membros com papel 'staff'
        $clinica1->members()->attach($staff->id, ['role' => 'staff']);

        // Adiciona o Médico na lista de membros com papel 'staff'
        $clinica1->members()->attach($medico->id, ['role' => 'staff']);


        // 6. Criar CLÍNICA 2 (Para testar isolamento - O intruso será dono desta)
        $clinica2 = Organization::create([
            'name' => 'Clínica do Intruso',
            'owner_id' => $intruso->id,
        ]);
        $clinica2->members()->attach($intruso->id, ['role' => 'owner']);

        // -------------------------------------------------------

        // 7. Criar Pacientes para a Clínica 1
        $paciente1 = Patient::create([
            'organization_id' => $clinica1->id,
            'name' => 'Maria Silva',
            'email' => 'maria@gmail.com',
            'phone' => '11999999999',
            'birthdate' => '1990-05-15',
        ]);

        $paciente2 = Patient::create([
            'organization_id' => $clinica1->id,
            'name' => 'José Santos',
            'email' => 'jose@gmail.com',
            'phone' => '11888888888',
            'birthdate' => '1985-10-20',
        ]);

        // 8. Criar Consultas (Appointments) na Clínica 1

        // Consulta Agendada (com o Médico Roberto)
        Appointment::create([
            'organization_id' => $clinica1->id,
            'patient_id' => $paciente1->id,
            'responsible_user_id' => $medico->id,
            'starts_at' => now()->addDays(1)->setHour(14)->setMinute(0), // Amanhã às 14h
            'duration_min' => 60,
            'status' => 'scheduled',
            'notes' => 'Primeira consulta de rotina.',
        ]);

        // Consulta Concluída (com o Dono Carlos)
        Appointment::create([
            'organization_id' => $clinica1->id,
            'patient_id' => $paciente2->id,
            'responsible_user_id' => $owner->id,
            'starts_at' => now()->subDays(2)->setHour(10)->setMinute(0), // 2 dias atrás
            'duration_min' => 30,
            'status' => 'done',
            'notes' => 'Paciente apresentou melhora.',
        ]);
    }
}
