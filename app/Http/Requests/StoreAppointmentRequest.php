<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $orgId = session('active_organization_id');

        return [
            'patient_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('patients', 'id')->where('organization_id', $orgId),
            ],
            'responsible_user_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('organization_user', 'user_id')->where('organization_id', $orgId),
            ],
            'starts_at' => 'required|date', // Data e Hora
            'duration_min' => 'required|integer|min:5',
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,done,canceled',
        ];
    }
}
