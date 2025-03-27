<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditOvertimeTakenInHoursColumnFromAttendances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('overtime_taken_in_hours',10,2)->nullable()->change();
            $table->decimal('total_late_hours',10,2)->nullable()->after('overtime_taken_in_hours');
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
            $table->string('overtime_taken_in_hours')->nullable()->change();
            $table->dropColumn('total_late_hours');
        });
    }
}
