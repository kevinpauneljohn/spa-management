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
            $table->date('start_shift');
            $table->date('end_shift')->nullable();
            $table->foreignUuid('user_id')->constrained();
            $table->foreignUuid('spa_id')->constrained();
            $table->decimal('start_money', 10,2)->nullable();
            $table->boolean('completed');
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
