<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('spa_id');
            $table->string('client_id')->nullable();
            $table->string('sales_id')->nullable();
            $table->string('service_id')->nullable();
            $table->string('service_name')->nullable();
            $table->string('batch');
            $table->string('amount');
            $table->string('start_time')->nullable();
            $table->string('appointment_type');
            $table->string('social_media_type')->nullable();
            $table->string('appointment_status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
