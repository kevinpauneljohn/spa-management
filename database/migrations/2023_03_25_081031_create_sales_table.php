<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('transaction_id');
            $table->string('spa_id');
            $table->string('amount_paid');
            $table->string('payment_status');
            $table->string('user_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->foreign('spa_id')->references('id')->on('spas');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
