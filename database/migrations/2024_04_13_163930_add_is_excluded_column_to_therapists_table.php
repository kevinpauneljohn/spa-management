<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsExcludedColumnToTherapistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('therapists', function (Blueprint $table) {
            $table->boolean('is_excluded')->after('offer_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('therapists', function (Blueprint $table) {
            $table->dropColumn('is_excluded');
        });
    }
}
