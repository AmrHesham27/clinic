<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class patients_values extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public $patients = [
        [
            'patientName' => 'Ahmed Hesham',
            'age' => 27,
            'address' => 'lorem ipsum lorem ipsum',
            'phoneNumber' => '01551694277'
        ],
        [
            'patientName' => 'Amr Ahmed',
            'age' => 27,
            'address' => 'lorem ipsum lorem ipsum',
            'phoneNumber' => '01551694271'
        ],
        [
            'patientName' => 'Amr Nader',
            'age' => 27,
            'address' => 'lorem ipsum lorem ipsum',
            'phoneNumber' => '01551694270'
        ],
        [
            'patientName' => 'Nader Hesham',
            'age' => 27,
            'address' => 'lorem ipsum lorem ipsum',
            'phoneNumber' => '01551694279'
        ],
        [
            'patientName' => 'Eslam Hesham',
            'age' => 27,
            'address' => 'lorem ipsum lorem ipsum',
            'phoneNumber' => '01551694278'
        ],
        [
            'patientName' => 'Amr Nour',
            'age' => 27,
            'address' => 'lorem ipsum lorem ipsum',
            'phoneNumber' => '01551694227'
        ],
        [
            'patientName' => 'Amr Ahmed',
            'age' => 27,
            'address' => 'lorem ipsum lorem ipsum',
            'phoneNumber' => '01551694237'
        ],
        [
            'patientName' => 'Nour Hesham',
            'age' => 27,
            'address' => 'lorem ipsum lorem ipsum',
            'phoneNumber' => '01551694247'
        ],
        [
            'patientName' => 'Amr Khaled',
            'age' => 27,
            'address' => 'lorem ipsum lorem ipsum',
            'phoneNumber' => '01551694257'
        ],
        [
            'patientName' => 'Khaled Hesham',
            'age' => 27,
            'address' => 'lorem ipsum lorem ipsum',
            'phoneNumber' => '01551694267'
        ],
        [
            'patientName' => 'Youssef Khaled',
            'age' => 27,
            'address' => 'lorem ipsum lorem ipsum',
            'phoneNumber' => '01551694777'
        ],
    ];
    public function run()
    {
        foreach($this->patients as $patient){
            Patient::create($patient);
        }
    }
}
