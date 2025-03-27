<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPayrollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll', function (Blueprint $table) {
            $table->dropColumn([
                'name','Basic Pay','Allowance','HollidayPay','SSS',
                'PAGIBIG','PHILHEALTH','Loan','GrossPay','NetPay','PayrollRange',
                'type','amount','TotalCommission','TotalDays',
                'hour_render','late_hours','undertime'
            ]);
            $table->timestamp('date_start')->nullable()->after('employee_id');
            $table->timestamp('date_end')->nullable()->after('date_start');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll', function (Blueprint $table) {
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
            $table->string('type')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->bigInteger('TotalCommission')->nullable();
            $table->integer('TotalDays')->nullable();
            $table->float('hour_render')->nullable();
            $table->float('late_hours')->nullable();
            $table->float('undertime')->nullable();

            $table->dropColumn(['date_start','date_end']);
        });
    }
}
