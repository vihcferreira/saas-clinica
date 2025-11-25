<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'organization_id',
        'patient_id',
        'responsible_user_id',
        'starts_at',
        'duration_min',
        'status',
        'notes'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }
}
