<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;
    protected $table = 'visits';
    protected $fillable = [
        'patientId',
        'date',
        'startTime',
        'endTime',
        'visitType'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patientId');
    }
}
