<!-- Date range -->

<div class="form-group">
    <div class="input-group">
        <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
        </div>
        <input type="text" name="attendance_date" class="form-control float-right" id="attendance_date">
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
            $('#attendance_date').daterangepicker();
        });

        $(document).on('change','#attendance_date',function(){
            let date = $(this).val();

            $.ajax({
                url: '{!! route('get-attendance-by-date-range') !!}',
                method: 'get',
                data: {'date' : date},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done((response) => {
                $('#attendance-list').DataTable().ajax.reload(null, false);
            });
        })
    </script>
@endpush
