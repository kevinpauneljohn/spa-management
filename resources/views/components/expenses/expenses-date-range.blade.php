<!-- Date range -->
<div class="form-group">
    <div class="input-group">
        <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
        </div>
        <input type="text" name="date" class="form-control float-right" id="reservation">
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
            $('#reservation').daterangepicker()
        });
        $(document).on('change','#reservation',function(){
            let date = $(this).val();

            $.ajax({
                url: '{!! route('expenses.set.date') !!}',
                type: 'POST',
                data: {'date' : date},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done((result) => {
;                $('#expense-list').DataTable().ajax.reload(null, false);
            });
        })
    </script>
@endpush
