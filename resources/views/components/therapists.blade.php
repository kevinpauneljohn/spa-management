<div class="row mb-1">
    <div class="col-md-12">
        <div class="alert alert-default-info">
            <h5><i class="fas fa-info"></i> Note:</h5>
            Add masseur/masseuse to your spa who will serve your valued customers
        </div>

        @can('add therapist')
            <label for="exclude_status"></label><select name="exclude_status" id="exclude_status" class="form-control w-25 float-left">
                <option value="">--Select--</option>
                <option value="Exclude">Exclude</option>
                <option value="Unexclude">Unexclude</option>
            </select>
            <x-adminlte-button label="Submit" data-toggle="modal" data-target="#exclude-therapist-modal" id="exclude-therapist-modal-btn" class="bg-olive float-left"/>
            <x-adminlte-button label="Add Masseur/Masseuse" data-toggle="modal" data-target="#therapist-modal" id="therapist-modal-btn" class="bg-olive float-right"/>
        @endcan
    </div>
</div>
<div class="row mt-4">
    <div class="col-md-12 table-responsive">
        <table id="therapist-list" class="table table-bordered table-hover w-100" role="grid">
            <thead>
            <tr role="row">
                <th><label for="select_all"></label><input type="checkbox" name="select_all" id="select_all"></th>
                <th>Date Added</th>
                <th>Full Name</th>
                <th>Birth Date</th>
                <th>Mobile Number</th>
                <th>Email Address</th>
                <th>Gender</th>
                <th>Excluded</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

@if(auth()->user()->can('add therapist') || auth()->user()->can('edit therapist'))
    <div class="modal fade" id="therapist-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header bg-olive">
                    <h5 class="modal-title" id="exampleModalCenterTitle">New Masseur/Masseuse Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <x-forms.therapist-form spaId="{{$spaId}}" :therapist="$therapist ??''"/>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exclude-therapist-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header bg-olive">
                    <h5 class="modal-title">Exclude Masseur/Masseuse Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Select therapists</h5>
                </div>
            </div>
        </div>
    </div>
@endcan
@once
    @push('js')
        <script>
            $(document).ready(function() {
                $('#therapist-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('therapist.lists', ['id' => $spaId]) !!}',
                    columns: [
                        { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, className: 'text-center'},
                        { data: 'created_at', name: 'created_at'},
                        { data: 'fullname', name: 'fullname'},
                        { data: 'date_of_birth', name: 'date_of_birth'},
                        { data: 'mobile_number', name: 'mobile_number'},
                        { data: 'email', name: 'email'},
                        { data: 'gender', name: 'gender'},
                        { data: 'is_excluded', name: 'is_excluded'},
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                    ],
                    responsive:true,
                    order:[0,'desc'],
                    pageLength: 50
                });
            });

            $(document).on('click','#select_all',function(){
                $('.exclude_therapists').prop('checked', $('#select_all').is(':checked'));
            })

            let excludeTherapistModal = $('#exclude-therapist-modal')
            let exclude_therapists = '';
            let excludeStatus = '';
            $(document).on('click','#exclude-therapist-modal-btn',function(){
                exclude_therapists = $('input[name=exclude_therapists]:checked').map(function () {
                    return this.value;
                }).get();
                excludeStatus = $('#exclude_status').val();

                excludeTherapistModal.find('.modal-title').text(excludeStatus+' Masseur/Masseuse')

                $('#exclude-therapist-modal').find('.modal-body').html('<h5>Select therapists</h5>')

                if(exclude_therapists.length > 0)
                {
                    $.ajax({
                        url: '/get-selected-therapists',
                        type: 'get',
                        data: {excluded: exclude_therapists},
                        beforeSend: function(){

                        }
                    }).done(function(response){
                        let tableRow = '<table>';
                        $.each(response, function(key, value){
                            tableRow += '<tr><td>'+value.full_name+'</td></tr>'
                        })
                        tableRow += '</table><button type="button" class="btn btn-success mt-3 w-100 confirm-exclude-btn">'+excludeStatus+'</button>'

                        $('#exclude-therapist-modal').find('.modal-body').html(tableRow)
                    }).fail(function(xhr, status, error){
                        console.log(xhr)
                    }).always(function(){
                        $('#exclude_status').val('').change()
                    });
                }
            })

            $(document).on('click','.confirm-exclude-btn',function(){
                let url = '/exclude-therapists';
                if(excludeStatus === 'Unexclude')
                {
                    url = '/unexclude-therapists'
                }

                $.ajax({
                    url: url,
                    type: 'put',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {excluded: exclude_therapists},
                    beforeSend: function(){
                        excludeTherapistModal.find('.confirm-exclude-btn').attr('disabled',true).text('Excluding...')
                    }
                }).done(function(response){
                    if(response.success === true)
                    {
                        Toast.fire({
                            type: "success",
                            title: response.message
                        })
                        $('#therapist-list').DataTable().ajax.reload(null, false);
                        excludeTherapistModal.modal('toggle')
                    }else{
                        Toast.fire({
                            type: "warning",
                            title: response.message
                        })
                    }
                }).fail(function (xhr, status, error){
                    console.log(xhr)
                }).always(function(){
                    excludeTherapistModal.find('.confirm-exclude-btn').attr('disabled',false).text('Exclude')
                })
            })
        </script>
    @endpush
@endonce


