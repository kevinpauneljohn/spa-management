<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->enum('type',['voucher','coupon']);
            $table->boolean('is_amount');
            $table->decimal('price',10,2)->nullable();
            $table->decimal('amount',10,2)->nullable();
            $table->decimal('percent',5,2)->nullable();
            $table->foreignUuid('client_id')->nullable();
            $table->foreignUuid('sale_id')->nullable();
            $table->dateTime('date_claimed')->nullable();
            $table->foreignUuid('sales_id_claimed')->nullable();
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
        Schema::dropIfExists('discounts');
    }
}
