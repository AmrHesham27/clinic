<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingDays extends Model
{
    use HasFactory;
    public $table = 'workingDays';
    public $timestamps = false;
    protected $fillable = [
        'day',
        'working'
    ];
}
