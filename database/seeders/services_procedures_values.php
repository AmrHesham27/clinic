<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\services_procedures;

class services_procedures_values extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public $services_procedures = [
        [
            'serviceName' => 'examination',
            'price' => 250
        ],
        [
            'serviceName' => 'consultation',
            'price' => 200
        ],
        [
            'serviceName' => 'Tooth Filling',
            'price' => 900
        ],
        [
            'serviceName' => 'Teeth Cleanings',
            'price' => 200
        ],
        [
            'serviceName' => 'Teeth Whitening',
            'price' => 1500
        ],
    ];
    public function run()
    {
        foreach($this->services_procedures as $service){
            services_procedures::create($service);
        }
    }
}
