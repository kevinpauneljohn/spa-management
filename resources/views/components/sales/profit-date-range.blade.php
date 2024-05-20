<!-- Date range -->
<div class="form-group">
    <div class="input-group">
        <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
        </div>
        <input type="text" name="date" class="form-control float-right" id="profit-report">
    </div>
    <!-- /.input group -->
</div>

<!-- /.form group -->
@section('plugins.Moment',true)
@section('plugins.DateRangePicker',true)

@section('plugins.tempusdominusBootstrap4',true)
@push('js')
    <script>
        let profitReportComponent = $('#profit-report-component');
        $(document).ready(function(){
            //Date range picker
            $('#profit-report').daterangepicker()
        });
        $(document).on('change','#profit-report',function(){
            let date = $(this).val();

            $.ajax({
                url: '{!! route('get.spa.profit.by.date.range',['spa' => $spaId]) !!}',
                type: 'POST',
                data: {'date' : date},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done((response) => {
                console.log(response)
                profitReportComponent.find('#total-sales').text(response.sales);
                profitReportComponent.find('#total-expenses').text(response.expenses);
                profitReportComponent.find('#total-profit').text(response.profit);

                $('#date-range-title').text('As of '+response.startDate+' to '+response.endDate)
            });
        })
    </script>
@endpush
