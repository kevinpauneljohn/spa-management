<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddStartsAtExpiresAtStatusColumnToOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('owners', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['license']);
        });

        Schema::table('owners', function (Blueprint $table) {
            $table->uuid('license')->nullable()->change();
            $table->timestamp('starts_at')->nullable()->after('license');
            $table->timestamp('expires_at')->nullable()->after('starts_at');
            $table->enum('status', ['active', 'inactive','delayed'])->nullable()->after('expires_at');
        });

        Schema::table('owners', function (Blueprint $table) {
            // Recreate the foreign key (adjust 'licenses' and 'id' if needed)
            $table->foreign('license')->references('id')->on('licenses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('owners', function (Blueprint $table) {
            $table->dropForeign(['license']);
            $table->string('license')->nullable()->change();
            $table->dropColumn(['starts_at','expires_at','status']);
            $table->foreign('license')->references('id')->on('licenses');
        });
    }
}
