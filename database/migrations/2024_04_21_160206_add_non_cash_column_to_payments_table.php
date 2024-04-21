<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNonCashColumnToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('non_cash_payment',10,2)->nullable()->after('payment');
            $table->decimal('change',10,2)->nullable()->after('non_cash_payment');
            $table->decimal('payment',10,2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['non_cash_payment','change']);
            $table->decimal('payment',8,2)->nullable(false)->change();
        });
    }
}
