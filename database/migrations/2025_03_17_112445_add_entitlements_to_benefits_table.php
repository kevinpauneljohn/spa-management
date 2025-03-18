<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntitlementsToBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('benefits', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->decimal('daily_basic_pay',10,2)->nullable();
            $table->boolean('with_sss');
            $table->decimal('sss_monthly_contribution_basis',10,2)->nullable();
            $table->boolean('with_pag_ibig');
            $table->decimal('pag_ibig_monthly_contribution_basis',10,2)->nullable();
            $table->boolean('with_philhealth');
            $table->decimal('philhealth_monthly_contribution_basis',10,2)->nullable();
            $table->boolean('with_thirteenth_month_pay');
            $table->boolean('with_overtime_pay');
            $table->boolean('with_holiday_pay');
            $table->boolean('with_service_incentive_leaves');
            $table->boolean('with_maternity_leave');
            $table->boolean('with_paternity_leave');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('benefits', function (Blueprint $table) {
            $table->string('name')->after('employee_id')->nullable();
            $table->dropColumn([
                'daily_basic_pay',
                'with_sss',
                'sss_monthly_contribution_basis',
                'with_pag_ibig',
                'pag_ibig_monthly_contribution_basis',
                'with_philhealth',
                'philhealth_monthly_contribution_basis',
                'with_thirteenth_month_pay',
                'with_overtime_pay',
                'with_holiday_pay',
                'with_service_incentive_leaves',
                'with_maternity_leave',
                'with_paternity_leave'
            ]);
        });
    }
}
