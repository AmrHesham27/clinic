<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\workinghours;

class workingHours_values extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $workingHours = [
        [
            'day' => 'Saturday',
            'startTime' => '10:00',
            'endTime' => '11:00'
        ],
        [
            'day' => 'Saturday',
            'startTime' => '11:00',
            'endTime' => '12:00'
        ],
        [
            'day' => 'Saturday',
            'startTime' => '12:00',
            'endTime' => '13:00'
        ],
        [
            'day' => 'Saturday',
            'startTime' => '13:00',
            'endTime' => '14:00'
        ],
        [
            'day' => 'Saturday',
            'startTime' => '14:00',
            'endTime' => '15:00'
        ],
        [
            'day' => 'Sunday',
            'startTime' => '10:00',
            'endTime' => '11:00'
        ],
        [
            'day' => 'Sunday',
            'startTime' => '11:00',
            'endTime' => '12:00'
        ],
        [
            'day' => 'Sunday',
            'startTime' => '12:00',
            'endTime' => '13:00'
        ],
        [
            'day' => 'Sunday',
            'startTime' => '13:00',
            'endTime' => '14:00'
        ],
        [
            'day' => 'Sunday',
            'startTime' => '14:00',
            'endTime' => '15:00'
        ]
    ];
    public function run()
    {
        foreach($this->workingHours as $hour){
            workinghours::create($hour);
        }
    }
}
