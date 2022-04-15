<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class workinghours extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'day',
        'startTime',
        'endTime'
    ];
}
