<input type="hidden" class="form-control spaId" value="{{$spaId}}">
<div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-calendar-check"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Daily Appointment</span>
            <span class="info-box-number dailyAppointment"></span>
        </div>
    </div>
</div>
<div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-calendar-check"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Monthly Appointment</span>
            <span class="info-box-number monthlyAppointment"></span>
        </div>
    </div>
</div>
<div class="clearfix hidden-md-up"></div>
<div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Monthly New Client</span>
            <span class="info-box-number newClients"></span>
        </div>
    </div>
</div>
<div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-hand-holding-usd"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Daily Sales</span>
            <span class="info-box-number ">
                <span class="badge badge-danger text-default dailySales float-left"></span>
                @if(auth()->user()->hasRole('front desk') || auth()->user()->can('add sales'))
                    <span class="badge badge-info text-default float-right pointer btnEndShift">End Shift</span>
                @endif
            </span>
            <input type="hidden" class="form-control" id="daily_sales_amount">
        </div>
    </div>
</div>
@section('css')

@endsection

@push('js')
    @if(auth()->check())
        <script src="{{asset('js/alerts.js')}}"></script>
        <script>
            $(document).ready(function(){
                loadData($('.spaId').val());                
                function loadData(id)
                {
                    $.ajax({
                        'url' : '/transaction-data/'+id,
                        'type' : 'GET',
                        'data' : {},
                        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(result){
                            $('.dailyAppointment').text(result.daily_appointment);
                            $('.monthlyAppointment').text(result.monthly_appointment);
                            $('.newClients').text(result.new_clients);
                            $('.dailySales').html(result.total_sales);
                            $('#daily_sales_amount').val(result.sales);
                        }
                    });
                }
            });
        </script>
    @endif
@endpush