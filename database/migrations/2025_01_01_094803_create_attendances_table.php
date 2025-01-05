<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->dateTime('time_in')->nullable();
            $table->dateTime('time_out')->nullable();
            $table->dateTime('break_in')->nullable();
            $table->dateTime('break_out')->nullable();
            $table->boolean('is_overtime_allowed');
            $table->string('overtime_taken_in_hours')->nullable();
            $table->foreignUuid('user_id');//approved by an HR manager or the owner
            $table->string('userid')->nullable();//this will match the biometrics userid
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
        Schema::dropIfExists('attendances');
    }
}
