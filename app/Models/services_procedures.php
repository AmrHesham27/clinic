<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class services_procedures extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'serviceName',
        'price'
    ];
}
