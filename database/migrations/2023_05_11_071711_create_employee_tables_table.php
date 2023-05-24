<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_tables', function (Blueprint $table) {
            $table->id();
            // $table->string('employee_id');
            $table->string('user_id');
            $table->string('spa_id');
            $table->bigInteger('Monthly_Rate')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('employee_id')->references('id')->on('therapists');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('spa_id')->references('id')->on('spas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_tables');
    }
}
