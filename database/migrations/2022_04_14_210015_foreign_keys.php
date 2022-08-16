<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->foreign('patientId')->references('id')->on('patients')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });

        Schema::table('procedures', function (Blueprint $table) {
            $table->foreign('procedureId')->references('id')->on('services_procedures');
            $table->foreign('bill_id')->references('id')->on('bills');
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->foreign('visit_id')->references('id')->on('visits');
        });

        Schema::table('tests', function (Blueprint $table) {
            $table->foreign('visit_id')->references('id')->on('visits');
        });

        Schema::table('test_results', function (Blueprint $table) {
            $table->foreign('test_id')->references('id')->on('tests');
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->foreign('visit_id')->references('id')->on('visits');
        });

        Schema::table('diagnoses', function (Blueprint $table) {
            $table->foreign('visit_id')->references('id')->on('visits');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
