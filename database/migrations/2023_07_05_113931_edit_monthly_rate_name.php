<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditMonthlyRateName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_tables', function (Blueprint $table) {
            $table->renameColumn('Monthly_Rate', 'Daily_Rate');
            // $table->float('Daily_Rate')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_table', function (Blueprint $table) {
            //
        });
    }
}
