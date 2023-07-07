<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewPayslipColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll', function (Blueprint $table) {
            $table->float('hour_render')->nullable();
            $table->float('late_hours')->nullable();
            $table->float('undertime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll', function (Blueprint $table) {
            //
        });
    }
}
