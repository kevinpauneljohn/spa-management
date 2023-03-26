<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_certificates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('gc_code');
            $table->string('spa_id');
            $table->string('amount');
            $table->string('buyer')->nullable();
            $table->string('consumer')->nullable();
            $table->string('expiration_date')->nullable();
            $table->string('date_bought')->nullable();
            $table->string('date_consumed')->nullable();
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
        Schema::dropIfExists('gift_certificates');
    }
}
