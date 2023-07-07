@can('add expenses')
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#expense-modal" id="add-expense">Add</button>
@endcan

@push('js')
    @can('add expenses')
        <script>
            let spaId = '{{$spaId}}';
            $(document).on('click','#add-expense', function(){
                expenseModal.find('.modal-title').text('Add Expense');
                expenseModal.find('form').trigger('reset');
                expenseModal.find('.error').remove();
                expenseModal.find('.is-invalid').removeClass('is-invalid');
                expenseModal.find('form').removeClass('edit-expense').addClass('add-expense');
            });


            $(document).on('submit','.add-expense', function(form){
                form.preventDefault();
                expenseModal.find('.error').remove();
                expenseModal.find('.is-invalid').removeClass('is-invalid');
                let data = $(this).serializeArray().concat({'name' : 'spa_id', 'value' : spaId});

                $.ajax({
                    url: '/expenses',
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    beforeSend: function(){
                        expenseModal.find('button[type=submit]').attr('disabled',true);
                        expenseModal.find('.modal-content').append(overlay);
                    }
                }).done((result) => {
                    console.log(result);
                    tableName.DataTable().ajax.reload(null, false);

                    if(result.success === true)
                    {
                        Toast.fire({
                            type: 'success',
                            title: result.message
                        });
                        expenseModal.find('form').trigger('reset');
                    }

                }).fail( (xhr, status, error) => {

                    $.each(xhr.responseJSON.errors, function(key, value){
                        expenseModal.find('#'+key).addClass('is-invalid');
                        expenseModal.find('.'+key).append('<p class="error">'+value+'</p>');
                    });
                })
                    .always( () => {
                        expenseModal.find('.overlay').remove();
                        expenseModal.find('button[type=submit]').attr('disabled',false);
                    });
            });
        </script>
    @endcan
@endpush
