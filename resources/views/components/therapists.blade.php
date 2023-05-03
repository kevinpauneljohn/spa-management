<div class="row mb-1">
    <div class="col-md-12">
        <div class="alert alert-default-info">
            <h5><i class="fas fa-info"></i> Note:</h5>
            Add masseur/masseuse to your spa who will serve your valued customers
        </div>
        @can('add therapist')
            <x-adminlte-button label="Add Masseur/Masseuse" data-toggle="modal" data-target="#therapist-modal" id="therapist-modal-btn" class="bg-olive float-right"/>
        @endcan
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table id="therapist-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
            <thead>
            <tr role="row">
                <th>Date Added</th>
                <th>Full Name</th>
                <th>Birth Date</th>
                <th>Mobile Number</th>
                <th>Email Address</th>
                <th>Gender</th>
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
                    <x-therapist-form spaId="{{$spaId}}" :therapist="$therapist ??''"/>
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
                        { data: 'created_at', name: 'created_at'},
                        { data: 'fullname', name: 'fullname'},
                        { data: 'date_of_birth', name: 'date_of_birth'},
                        { data: 'mobile_number', name: 'mobile_number'},
                        { data: 'email', name: 'email'},
                        { data: 'gender', name: 'gender'},
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                    ],
                    responsive:true,
                    order:[0,'desc'],
                    pageLength: 50
                });
            });
        </script>
    @endpush
@endonce


