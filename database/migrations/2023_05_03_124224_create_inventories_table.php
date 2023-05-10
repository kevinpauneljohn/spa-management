<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->uuid('spa_id');
            $table->uuid('owner_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('quantity');
            $table->integer('restock_limit');
            $table->string('unit',50);
            $table->unsignedBigInteger('category');
            $table->string('sku',100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('spa_id')->references('id')->on('spas');
            $table->foreign('owner_id')->references('id')->on('owners');
            $table->foreign('category')->references('id')->on('inventory_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
