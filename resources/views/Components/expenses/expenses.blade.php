<div>


    <table id="expense-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
        <thead>
        <tr role="row">
            <th>Date Added</th>
            <th>Title</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
        </thead>
    </table>
</div>


<div class="modal fade" id="expense-modal" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="expense-form">
                @csrf
                <div class="modal-header bg-olive">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group title">
                        <label for="title">Title</label><span class="required">*</span>
                        <input type="text" name="title" class="form-control" id="title" />
                    </div>
                    <div class="form-group description">
                        <label for="description">Description</label><span class="required">*</span>
                        <textarea name="description" class="form-control" id="description" ></textarea>
                    </div>
                    <div class="form-group amount">
                        <label for="amount">Amount</label><span class="required">*</span>
                        <input type="number" name="amount" class="form-control" id="amount" step="any" min="0" value="0"/>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


@section('css')
<style>
    .error{
        color:red;
    }
</style>
@endsection

@push('js')
    @if(auth()->check())
        <script src="{{asset('js/alerts.js')}}"></script>
        <script>
            $(document).ready(function(){
                $('#expense-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('spa.expenses',['spa' => $spaId]) !!}',
                    columns: [
                        { data: 'created_at', name: 'created_at', className: 'text-center' },
                        { data: 'title', name: 'title'},
                        { data: 'description', name: 'description'},
                        { data: 'amount', name: 'amount'},
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                    ],
                    responsive:true,
                    order:[0,'desc'],
                    pageLength: 5
                });
            });
        </script>
        @if(auth()->user()->can('edit expenses'))
            <script>
                let expenseId;
                let expenseModal = $('#expense-modal');
                let overlay = '<div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>';
                let tableName = $('#expense-list');

                $(document).on('click','.edit-expense-btn', function(){
                    expenseId  = this.id;
                    expenseModal.find('.modal-title').text('Edit Expense');
                    expenseModal.find('form').removeClass('add-expense').addClass('edit-expense');

                    $.ajax({
                        url: '/expenses/'+expenseId+'/edit',
                        type: 'GET',
                        dataType: 'json',
                        beforeSend: function(){
                            expenseModal.find('button[type=submit]').attr('disabled',true);
                            expenseModal.find('.modal-content').append(overlay);
                        }
                    }).done((result) => {
                        $.each(result, function(key, value){
                            expenseModal.find('#'+key).val(value);
                        });
                    }).fail((xhr, status, error) => {
                        console.log(xhr);
                    }).always(() => {
                        expenseModal.find('.overlay').remove();
                        expenseModal.find('button[type=submit]').attr('disabled',false);
                    });
                });

                $(document).on('submit','.edit-expense', function(form){
                    form.preventDefault();

                    let data = $(this).serializeArray();
                    expenseModal.find('.error').remove();
                    expenseModal.find('.is-invalid').removeClass('is-invalid');

                    $.ajax({
                        url: '/expenses/'+expenseId,
                        type: 'PATCH',
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
                            expenseModal.modal('toggle');
                        }
                        else if(result.success === false)
                        {
                            Toast.fire({
                                type: 'warning',
                                title: result.message
                            });
                        }

                    })
                        .fail( (xhr, status, error) => {
                            console.log(xhr)

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
        @endif

        @can('delete expenses')
            <script>
                $(document).on('click','.delete-expense-btn',function (){
                    let id = this.id;

                    $tr = $(this).closest('tr');
                    id = this.id;
                    let data = $tr.children('td').map(function () {
                        return $(this).text();
                    }).get();

                    swal.fire({
                        title: "Delete Expense: "+data[1]+"?",
                        text: "Please ensure and then confirm!",
                        type: "warning",
                        showCancelButton: !0,
                        confirmButtonText: "Yes!",
                        cancelButtonText: "No!",
                        reverseButtons: !0
                    }).then(function (e) {
                        if (e.value === true) {
                            $.ajax({
                                'url' : '/expenses/'+id,
                                'type' : 'DELETE',
                                'data': id,
                                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                beforeSend: function () {
                                    $('#delete-spa-form').find('.delete-spa-modal-btn').val('Deleting ... ').attr('disabled',true);
                                },success: function (result) {
                                    if(result.success === true) {

                                        tableName.DataTable().ajax.reload(null, false);

                                        swal.fire("Done!", result.message, "success");

                                    } else {
                                        swal.fire("Warning!", result.message, "warning");
                                    }

                                },error: function(xhr, status, error){
                                    console.log(xhr);
                                }
                            });
                        } else {
                            e.dismiss;
                        }
                    });
                });
            </script>
        @endcan
    @endif
@endpush
