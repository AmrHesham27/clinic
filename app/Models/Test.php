<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $table = 'tests';
    protected $fillable = [
        'visit_id',
        'testName',
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
