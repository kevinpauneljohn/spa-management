<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('spa_id');
            $table->string('service_id');
            $table->string('service_name');
            $table->string('amount');
            $table->string('therapist_1');
            $table->string('therapist_2')->nullable();
            $table->string('client_id');
            $table->string('start_time');
            $table->string('end_time');
            $table->string('plus_time')->nullable();
            $table->string('discount_rate')->nullable();
            $table->string('discount_amount')->nullable();
            $table->string('tip')->nullable();
            $table->string('rating');
            $table->string('sales_type');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('spa_id')->references('id')->on('spas');
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('client_id')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
