<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->unsignedBigInteger('employee_id');


            $table->boolean('Monday')->default(0);
            $table->boolean('Tuesday')->default(0);
            $table->boolean('Wednesday')->default(0);
            $table->boolean('Thursday')->default(0);
            $table->boolean('Friday')->default(0);
            $table->boolean('Saturday')->default(0);
            $table->boolean('Sunday')->default(0);
            
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('employee_id')->references('id')->on('employee_tables');

            
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
        Schema::dropIfExists('shifts');
    }
}
