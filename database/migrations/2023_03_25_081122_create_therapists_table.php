<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTherapistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('therapists', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('spa_id');
            $table->string('user_id');
            $table->string('gender');
            $table->string('certificate')->nullable();
            $table->string('commission_percentage')->nullable();
            $table->string('commission_flat')->nullable();
            $table->string('allowance')->nullable();
            $table->string('offer_type');
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('therapists');
    }
}
