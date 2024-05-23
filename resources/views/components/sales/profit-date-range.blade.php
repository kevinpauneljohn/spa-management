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

            // $('#profit-report').attr('value', '01/01/2018 - 01/15/2018');
            $('#profit-report').daterangepicker();
            setTimeout(function(){
                $('#profit-report').val('{{now()->startOfMonth()->format('m/d/Y')}} - {{now()->endOfMonth()->format('m/d/Y')}}').change();
            },1000)

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
                profitReportComponent.find('#total-sales').html('&#8369; '+response.sales);
                profitReportComponent.find('#total-expenses').html('&#8369; '+response.expenses);
                profitReportComponent.find('#total-profit').html('&#8369; '+response.profit);

                $('#date-range-title').text('As of '+response.startDate+' to '+response.endDate)
            });
        })
    </script>
@endpush
