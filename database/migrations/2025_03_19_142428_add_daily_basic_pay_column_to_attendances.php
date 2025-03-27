<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDailyBasicPayColumnToAttendances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('daily_basic_pay', 10, 2)->nullable()->after('schedule_id');
            $table->decimal('late_deductions', 10, 2)->nullable()->after('daily_basic_pay');;
            $table->decimal('overtime_pay', 10, 2)->nullable()->after('late_deductions');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['daily_basic_pay','late_deductions','overtime_pay']);
        });
    }
}
