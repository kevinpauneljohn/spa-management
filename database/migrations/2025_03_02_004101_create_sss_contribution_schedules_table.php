<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSssContributionSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sss_contribution_schedules', function (Blueprint $table) {
            $table->id();
            $table->double('start_range_of_compensation')->nullable();
            $table->double('end_range_of_compensation')->nullable();
            $table->double('regular_ss_employees_compensation')->nullable();
            $table->double('mandatory_provident_fund')->nullable();
            $table->double('employer_regular_ss_contribution')->nullable();
            $table->double('employer_mpf_contribution')->nullable();
            $table->double('employees_compensation')->nullable();
            $table->double('employees_regular_ss_contribution')->nullable();
            $table->double('employees_mpf_contribution')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sss_contribution_schedules');
    }
}
