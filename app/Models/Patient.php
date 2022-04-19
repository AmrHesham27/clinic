<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Visit;

class Patient extends Model
{
    use HasFactory;
    protected $table = 'patients';
    protected $fillable = [
        'patientName',
        'age',
        'address',
        'phoneNumber',
    ];

    public function visits()
    {
        return $this->hasMany(Visit::class, 'patientId');
    }
}
