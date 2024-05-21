<!-- Date range -->
<div class="form-group">
    <div class="input-group">
        <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
        </div>
        <input type="text" name="date" class="form-control float-right" id="expenses-date-range">
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
            $('#expenses-date-range').daterangepicker()
            setTimeout(function(){
                $('#expenses-date-range').val('{{now()->startOfMonth()->format('m/d/Y')}} - {{now()->endOfMonth()->format('m/d/Y')}}').change();
            },50)
        });
        $(document).on('change','#expenses-date-range',function(){
            let date = $(this).val();

            $.ajax({
                url: '{!! route('expenses.set.date') !!}',
                type: 'POST',
                data: {'date' : date},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done((result) => {
              $('#expense-list').DataTable().ajax.reload(null, false);
            });
        })
    </script>
@endpush
