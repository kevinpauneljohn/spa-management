<!-- Date range -->

<div class="form-group">
    <div class="input-group">
        <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
        </div>
        <input type="text" name="date" class="form-control float-right" id="profit-report-{{$spaId}}">
    </div>
    <!-- /.input group -->
</div>

<!-- /.form group -->
@section('plugins.Moment',true)
@section('plugins.DateRangePicker',true)

@section('plugins.tempusdominusBootstrap4',true)
@push('js')
    <script>
        $(document).ready(function(){
            //Date range picker
            $('#profit-report-{{$spaId}}').daterangepicker();
            setTimeout(function(){
                $('#profit-report-{{$spaId}}').val('{{now()->startOfMonth()->format('m/d/Y')}} - {{now()->endOfMonth()->format('m/d/Y')}}').change();
            },500)

        });

        $(document).on('change','#profit-report-{{$spaId}}',function(){
            let date = $(this).val();

            $.ajax({
                url: '{!! route('get.spa.profit.by.date.range',['spa' => $spaId]) !!}',
                type: 'POST',
                data: {'date' : date},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done((response) => {

                $('#profit-report-component-{{$spaId}}').find('#total-sales').html('&#8369; '+response.sales);
                $('#profit-report-component-{{$spaId}}').find('#total-expenses').html('&#8369; '+response.expenses);
                $('#profit-report-component-{{$spaId}}').find('#total-profit').html('&#8369; '+response.profit);

            });
        })
    </script>
@endpush
