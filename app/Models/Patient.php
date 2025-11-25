<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'birthdate', 'organization_id'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
