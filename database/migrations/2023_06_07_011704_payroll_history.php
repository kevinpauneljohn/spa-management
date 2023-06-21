<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PayrollHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('name');
            $table->bigInteger('Basic Pay')->nullable();
            $table->bigInteger('Allowance')->nullable();
            $table->bigInteger('HollidayPay')->nullable();
            $table->bigInteger('SSS')->nullable();
            $table->bigInteger('PAGIBIG')->nullable();
            $table->bigInteger('PHILHEALTH')->nullable();
            $table->bigInteger('Loan')->nullable();
            $table->bigInteger('GrossPay')->nullable();
            $table->bigInteger('NetPay')->nullable();
            $table->string('PayrollRange')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employee_tables');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll');
    }
}
