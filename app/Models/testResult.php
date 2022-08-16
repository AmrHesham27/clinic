<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class testResult extends Model
{
    use HasFactory;
    protected $fillable = [
        'test_id',
        'result',
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
