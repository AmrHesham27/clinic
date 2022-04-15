<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\patients_values;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(workingHours_values::class);
        $this->call(services_procedures_values::class);
        $this->call(patients_values::class);
    }
}
