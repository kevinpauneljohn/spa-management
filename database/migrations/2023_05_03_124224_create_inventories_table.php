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
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('quantity');
            $table->string('unit',50);
            $table->char('category',200)->nullable();
            $table->string('sku',100)->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('inventories');
    }
}
