<!-- Date range -->

<div class="form-group">
    <div class="input-group">
        <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
        </div>
            <input type="text" name="payroll_cut_off" class="form-control" id="payroll_cut_off">
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
            $('#payroll_cut_off').daterangepicker();
        });

        $(document).on('change','#payroll_cut_off', function(){
            let date = $(this).val();

            $.ajax({
                url: '{!! route('get-payroll-by-date-range') !!}',
                method: 'get',
                data: {'date' : date},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done((response) => {
                console.log(response)
                $('#payroll-list').DataTable().ajax.reload(null, false);
            });
        })

    </script>
@endpush
