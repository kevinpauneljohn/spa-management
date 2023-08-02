<div class="modal fade"  id="rateModal" tabindex="-1" aria-labelledby="rateModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" >
      <div class="modal-content" style="border: 2px;border-radius: 10px;">
          <form id="update-employee-daily-rate-form">
              @csrf
            <div class="modal-header bg-olive">
        {{--          <h5 class="modal-title" id="rateModal">Employee rate</h5>--}}
              <h3 class="modal-title" id="ratename">Employee rate</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group Daily_Rate">
                    <label for="emp_rate">Set the daily rate</label>
                    <input name="Daily_Rate" type="text" id="Daily_Rate" class="form-control">
                    <input name="id" type="hidden" id="hiddenID">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" id="save-rate" class="btn btn-primary">Save changes</button>
            </div>
          </form>
      </div>
    </div>
  </div>
@once
    @push('js')
        <script>
            $(document).on('submit','#update-employee-daily-rate-form',function(form){
                form.preventDefault();
                $('#rateModal').find('.error').remove();
                $('#rateModal').find('.is-invalid').removeClass('is-invalid');
                var data = $(this).serializeArray();
                var employeeId = $('#hiddenID').val();

                $.ajax({
                    url: '/update-employee-rate/'+employeeId,
                    type: 'PUT',
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: data,
                    beforeSend: function(){

                    }
                }).done(function (response){
                    if(response.success === true)
                    {
                        swal({
                            title: "Update Successful",
                            text: response.message,
                            icon: "success",
                            buttons: {
                                confirm: {
                                    text: 'OK',
                                    className: 'btn-success'
                                }
                            },
                        }).then(function (){
                            $('#rateModal').modal('toggle');
                        });
                        $('#tbl_employee').DataTable().ajax.reload(null, false);
                    }
                }).fail(function(xhr, status, error){
                    $.each(xhr.responseJSON.errors, function (key, value){
                        $('#rateModal').find('.'+key).append('<p class="error text-sm text-danger">'+value+'</p>')
                        $('#rateModal').find('#'+key).addClass('is-invalid')
                    })
                });
            });
            // Update MonthlyRate
            $(document).on('click', '#saverate', function() {
                var employeeId = $('#hiddenID').val();
                var newRate = $('#emp_rate').val();

                $.ajax({
                    url: '/updateEmployeeRate/' + employeeId,
                    type: 'PUT',
                    data: {
                        newRate: newRate,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        swal({
                            title: "Update Successful",
                            text: 'Operation completed successfully.',
                            icon: "success",
                            buttons: {
                                confirm: {
                                    text: 'OK',
                                    className: 'btn-success'
                                }
                            },
                        }).then(function(){
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        </script>
    @endpush
@endonce
