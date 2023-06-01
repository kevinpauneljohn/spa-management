<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('start_shift', 20);
            $table->string('end_shift', 20)->nullable();
            $table->string('user_id');
            $table->string('spa_id');
            $table->string('start_money', 10);
            $table->string('confirm_start_shift', 10)->nullable();
            $table->string('confirm_end_shift', 10)->nullable();
            $table->string('confirm_start_money', 10)->nullable();
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
        Schema::dropIfExists('sales_shifts');
    }
}
