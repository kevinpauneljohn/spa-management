<form id="benefits-form">
    @csrf
    <h4>Daily Basic Pay</h4>
    <div class="row">
        <div class="col-lg-4 daily_basic_pay">
            <label for="daily_basic_pay"></label>
            <input type="number" step="any" name="daily_basic_pay" id="daily_basic_pay" class="form-control" placeholder="Input daily salary amount here" value="{{!is_null($employee->benefit) ? $employee->benefit->daily_basic_pay : ''}}">
        </div>
    </div>
    <hr class="mt-4">
    <h4>Entitlements</h4>
    <div class="row">
        <div class="col-lg-4 with_sss">
            <input type="checkbox" name="with_sss" id="with_sss" @if(!is_null($employee->benefit) && $employee->benefit->with_sss) checked @endif> &nbsp; Social Security System
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 with_pag_ibig">
            <input type="checkbox" name="with_pag_ibig" id="with_pag_ibig" @if(!is_null($employee->benefit) && $employee->benefit->with_pag_ibig) checked @endif> &nbsp; Pag-IBIG
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 with_philhealth">
            <input type="checkbox" name="with_philhealth" id="with_philhealth" @if(!is_null($employee->benefit) && $employee->benefit->with_philhealth) checked @endif> &nbsp; PhilHealth
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 with_thirteenth_month_pay">
            <input type="checkbox" name="with_thirteenth_month_pay" id="with_thirteenth_month_pay" @if(!is_null($employee->benefit) && $employee->benefit->with_thirteenth_month_pay) checked @endif> &nbsp; 13th Month Pay
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 with_overtime_pay">
            <input type="checkbox" name="with_overtime_pay" id="with_overtime_pay" @if(!is_null($employee->benefit) && $employee->benefit->with_overtime_pay) checked @endif> &nbsp; Overtime Pay
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 with_holiday_pay">
            <input type="checkbox" name="with_holiday_pay" id="with_holiday_pay" @if(!is_null($employee->benefit) && $employee->benefit->with_holiday_pay) checked @endif> &nbsp; Holiday Pay
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 with_service_incentive_leave">
            <input type="checkbox" name="with_service_incentive_leave" id="with_service_incentive_leave" @if(!is_null($employee->benefit) && $employee->benefit->with_service_incentive_leave) checked @endif> &nbsp; Service Incentive Leave (SIL)
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 with_maternity_leave">
            <input type="checkbox" name="with_maternity_leave" id="with_maternity_leave" @if(!is_null($employee->benefit) && $employee->benefit->with_maternity_leave) checked @endif> &nbsp; Maternity Leave
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 with_maternity_leave">
            <input type="checkbox" name="with_paternity_leave" id="with_paternity_leave" @if(!is_null($employee->benefit) && $employee->benefit->with_paternity_leave) checked @endif> &nbsp; Paternity Leave
        </div>
    </div>

    <hr class="mt-4">
    <h4>Contributions</h4>
    <div class="row">
        <div class="col-lg-4 social_security_system">
            <label>Monthly Social Security System (SSS)</label>
            <input type="number" name="social_security_system" id="social_security_system" class="form-control" value="{{!is_null($employee->benefit) ? $employee->benefit->sss_monthly_contribution_basis : ''}}">
        </div>
        <div class="col-lg-4 pagibig">
            <label>Monthly Pag-IBIG</label>
            <input type="number" name="pagibig" id="pagibig" class="form-control" value="{{!is_null($employee->benefit) ? $employee->benefit->pag_ibig_monthly_contribution_basis : ''}}">
        </div>
        <div class="col-lg-4 philhealth">
            <label>Monthly PhilHealth</label>
            <input type="number" name="philhealth" id="philhealth" class="form-control" value="{{!is_null($employee->benefit) ? $employee->benefit->philhealth_monthly_contribution_basis : ''}}">
        </div>
    </div>
    <input type="hidden" name="employee_id" value="{{$employee->id}}">
    <input type="hidden" name="owner_id" value="{{$employee->owner_id}}">
    <div class="row mt-4">
        <div class="col-lg-12">
            <button type="submit" class="btn btn-primary save-benefits-button">Save</button>
        </div>
    </div>
</form>

@push('js')
    <script src="{{asset('/js/benefits/benefits.js')}}"></script>
@endpush


