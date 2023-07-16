@if(auth()->user()->hasRole('front desk') || auth()->user()->can('add sales'))
    <span class="pointer btnEndShift">End Shift</span>
@endif