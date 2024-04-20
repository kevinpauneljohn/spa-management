<!-- Date range -->
<div class="form-group">
    <label for="sales-dates">Browse Sales By Date</label>
    <div class="input-group">
        <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
        </div>
        <input type="text" name="sales_date" class="form-control float-right" id="sales-dates">
    </div>
    <!-- /.input group -->
</div>

@once
    @section('plugins.Moment',true)
    @section('plugins.DateRangePicker',true)

    @section('plugins.tempusdominusBootstrap4',true)

    @push('js')
        <script>
            $(document).ready(function(){
                //Date range picker
                $('#sales-dates').daterangepicker().change()
            });

            $(document).on('change','#sales-dates',function(){
                let date = $(this).val();

                $.ajax({
                    url: '{!! route('display-sales-by-date-selected',['spaId' => $spaId]) !!}',
                    type: 'POST',
                    data: {'date' : date},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done((result) => {
                    console.log(result)
                    $('#dashboard-sales-table-list').DataTable().ajax.reload(null, false);
                });
            });
        </script>
    @endpush
@endonce
