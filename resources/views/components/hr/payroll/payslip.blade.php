<table class="table table-bordered table-hover">
    <tr>
        <td style="width: 20%">Name</td>
        <td id="employee_name" colspan="3">{{ucwords($employee->user->fullname)}}</td>
    </tr>
    <tr>
        <td>Pay Period</td>
        <td colspan="3">{{\Carbon\Carbon::parse($payroll->date_start)->format('M d, Y')}} to {{\Carbon\Carbon::parse($payroll->date_end)->format('M d, Y')}}</td>
    </tr>
</table>
<table class="table table-bordered table-hover">
    <tr>
        <td><span class="text-bold">RATE PER DAY</span></td>
        <td>{{number_format($employee->benefit->daily_basic_pay,2)}} PHP</td>
        <td><span class="text-muted">No. Of Days Worked:</span></td>
        <td>{{$daysWorked}}</td>
        <td><span class="text-muted">Basic Pay:</span></td>
        <td>{{number_format($grossBasicPay,2)}} PHP</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td><span class="text-muted">No. Of Days Absent:</span></td>
        <td>{{$daysWorked}}</td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td><span class="text-bold">Total Overtime Hours</span></td>
        <td>{{number_format($attendance->sum('overtime_taken_in_hours'),2)}}</td>
        <td></td>
        <td></td>
        <td><span class="text-muted">Total Overtime Pay</span></td>
        <td>{{number_format($attendance->sum('overtime_pay'),2)}}</td>
    </tr>
    <tr>
        <td colspan="5"><span class="text-muted">Legal Holiday:</span></td>
        <td>N/A</td>
    </tr>
    <tr>
        <td colspan="5"><span class="text-muted">Special Holiday:</span></td>
        <td>N/A</td>
    </tr>
    <tr>
        <td colspan="6">&nbsp;</td>
    </tr>
    <tr class="bg-light">
        <td colspan="6"><span class="text-bold">Allowances & Additional Pays</span></td>
    </tr>
    @foreach($additionalPays as $additionalPay)
        <tr>
            <td colspan="5"><span class="text-muted">{{ucwords($additionalPay->name)}}</span></td>
            <td>{{number_format($additionalPay->amount,2)}} PHP</td>
        </tr>
    @endforeach
    <tr class="bg-light">
        <td colspan="5"><span class="text-bold">Total Additional Pay:</span></td>
        <td>{{number_format($additionalPays->sum('amount'),2)}} PHP</td>
    </tr>
    <tr>
        <td colspan="6">&nbsp;</td>
    </tr>
    <tr class="bg-light">
        <td colspan="6"><span class="text-bold">Deductions</span></td>
    </tr>
    <tr>
        <td><span class="text-bold">Total Late Hours</span></td>
        <td>{{number_format($attendance->sum('total_late_hours'),2)}}</td>
        <td></td>
        <td></td>
        <td><span class="text-muted">Total Late Deductions</span></td>
        <td>{{number_format($attendance->sum('late_deductions'),2)}}</td>
    </tr>
    @foreach($deductions->get() as $deduction)
        <tr>
            <td colspan="5"><span class="text-muted">{{ucwords($deduction->name)}}</span></td>
            <td>{{number_format($deduction->amount,2)}} PHP</td>
        </tr>
    @endforeach
    <tr class="bg-light">
        <td colspan="5"><span class="text-bold">Total Deductions:</span></td>
        <td>{{number_format($deductions->sum('amount'),2)}}</td>
    </tr>
    <tr class="bg-gray-light">
        <td colspan="5"><span class="text-bold">Net Pay:</span></td>
        <td><span class="text-bold">{{number_format($netPay,2)}} PHP</span></td>
    </tr>
</table>
