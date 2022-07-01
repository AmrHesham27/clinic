<?php

namespace Database\Seeders;

use App\Models\WorkingDays;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class workingDays_values extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public $workingDays = [
        [
            'day' => 'Saturday',
            'working' => 1
        ],
        [
            'day' => 'Sunday',
            'working' => 1
        ],
        [
            'day' => 'Monday',
            'working' => 1
        ],
        [
            'day' => 'Tuesday',
            'working' => 1
        ],
        [
            'day' => 'Wednesday',
            'working' => 1
        ],
        [
            'day' => 'Thursday',
            'working' => 1
        ],
        [
            'day' => 'Friday',
            'working' => 0
        ],
    ];
    public function run()
    {
        foreach($this->workingDays as $day){
            WorkingDays::create($day);
        }
    }
}
