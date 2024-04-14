
@extends('adminlte::page')

@section('title', ucwords($spa->name))

@section('content_header')
    <h1></h1>
@stop
@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h3 class="text-cyan"><i class="fas fa-spa"></i> {{ucwords($spa->name)}}</h3>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('owner.my.spas')}}">Spa</a> </li>
                <li class="breadcrumb-item active">{{ucwords($spa->name)}} </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <span class="float-left">
                        <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#data" data-toggle="tab">Services</a></li>
                        <li class="nav-item"><a class="nav-link" href="#therapists" data-toggle="tab">Masseur/Masseuse</a></li>
                    </ul>
                    </span>
                    <span class="float-right">
                        @can('access pos')
                            <a href="{{route('receptionist.dashboard',['id' => $spa->id])}}" class="btn btn-default">Point Of Sale</a>
                        @endcan
                            <a href="{{route('spa.calendar',['spa' => $spa->id])}}" class="btn btn-default">Calendar</a>
                    </span>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="data">
                            <div class="alert alert-default-info">
                                <h5><i class="fas fa-info"></i> Note:</h5>
                                Create services you offer to your customers.
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    @can('add service')
                                        <x-service.add-service-button />
                                    @endcan
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="service-list" class="table table-bordered table-hover" role="grid" style="width:100%;">
                                        <thead>
                                        <tr role="row">
                                            <th>Date Added</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th>Duration</th>
                                            <th>Category</th>
                                            <th>Multiple Masseur</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="therapists">
                            <x-therapists :spaId="$spa->id"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-olive">
                <div class="card-header">
                    <h3 class="card-title">Spa Details</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                    <p class="text-muted">{{ucwords($spa->address)}}</p>

                    <hr>

                    <strong><i class="fas fa-bed mr-1"></i> Rooms</strong>

                    <p class="text-muted">
                        <span class="tag tag-primary">{{$spa->number_of_rooms}} available rooms</span>
                    </p>

                    <hr>

                    <strong><i class="fas fa-user-friends mr-1"></i> Employees</strong>

                    <p class="text-muted">
                        <span class="tag tag-primary">{{$spa->staff->count()}}</span>
                    </p>

                    <hr>

                    <strong><i class="fas fa-user-cog mr-1"></i> Therapists</strong>

                    <p class="text-muted">
                        <span class="tag tag-primary">{{$spa->therapists->count()}}</span>
                    </p>
                </div>
                <!-- /.card-body -->
            </div>
        </div>

    </div>

    @can('add service')
        <x-service.service-modal>

            <x-slot name="form">
                <x-service.service :spa="$spa"/>
            </x-slot>

        </x-service.service-modal>
    @endcan

    @can('edit service')
        <div class="modal fade" id="update-service-modal">
            <form role="form" id="update-service-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Services Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="bs-stepper" id="bs-stepper-update">
                                <div class="bs-stepper-header" role="tablist">
                                    <div class="step" data-target="#edit-info-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="edit-info-part" id="edit-info-part-trigger">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">Info</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#edit-price-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="edit-price-part" id="edit-price-part-trigger">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">Pricing</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="bs-stepper-content">
                                    <div id="edit-info-part" class="content" role="tabpanel" aria-labelledby="edit-info-part-trigger">
                                        <div class="form-group edit_name">
                                            <label for="edit_name">Name</label><span class="required">*</span>
                                            <input type="text" name="edit_name" class="form-control" id="edit_name">
                                            <input type="hidden" name="edit_id" id="edit_id" class="form-control">
                                        </div>
                                        <div class="form-group edit_description">
                                            <label for="edit_description">Description</label><span class="required">*</span>
                                            <input type="text" name="edit_description" class="form-control" id="edit_description">
                                        </div>
                                        <button type="button" class="btn btn-default edit_closeModal" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary edit_info_next_btn" onclick="editServiceStepper.next()">Next</button>
                                    </div>
                                    <div id="edit-price-part" class="content" role="tabpanel" aria-labelledby="edit-price-part-trigger">
                                        <div class="form-group edit_duration">
                                            <label for="edit_duration">Duration</label><span class="required">*</span>
                                            <br />
                                            <select class="form-control edit-duration-select" name="edit_duration" id="edit_duration" style="width:100%;">

                                            </select>
                                        </div>
                                        <div class="form-group edit_price">
                                            <label for="price">Price</label>
                                            <input type="number" class="form-control" id="edit_price" name="edit_price">
                                        </div>
                                        <div class="form-group edit_price_per_plus_time">
                                            <label for="edit_price_per_plus_time">Plus time price every 15 minutes</label>
                                            <input type="number" class="form-control" id="edit_price_per_plus_time" name="edit_price_per_plus_time">
                                        </div>
                                        <div class="form-group edit_category">
                                            <label for="edit_category">Category</label>
                                            <select name="edit_category" class="form-control" id="edit_category">

                                            </select>
                                        </div>
                                        <button type="button" class="btn btn-default edit_price_previous_btn hiddenBtn" onclick="editServiceStepper.previous()">Previous</button>
                                        <button type="button" class="btn btn-primary edit_price_submit_btn update-service-btn hiddenBtn">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan
@stop

@section('plugins.Toastr',true)
@section('plugins.CustomAlert',true)
@section('plugins.CustomCSS',true)
@section('css')
<style>
    .multiple_masseur_check{
        display: flex;
        flex-direction: row;
        justify-content: center;
    }
</style>
@stop

@section('js')
    <script src="{{asset('js/clear_errors.js')}}"></script>
    <script>
        $(document).ready(function() {
            var spa_id = $('.spa-id').val();
            $('.duration-select').select2();
            $('.edit-duration-select').select2();

            $('#service-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('service.lists', ['id' => $spa["id"]]) !!}',
                columns: [
                    { data: 'created_at', name: 'created_at'},
                    { data: 'name', name: 'name'},
                    { data: 'description', name: 'description'},
                    { data: 'price', name: 'price'},
                    { data: 'duration', name: 'duration'},
                    { data: 'category', name: 'category'},
                    { data: 'multiple_masseur', name: 'multiple_masseur'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 50
            });

            // $('#addNewService').on('click', function() {
            //     $('#name').val('');
            //     $('#description').val('');
            //     $('#duration').val('');
            //     $('#price').val('');
            //     $('#category').val('');
            //     $('#add-new-service-modal').modal('show');
            // });
            //
            // $(document).on('click','.edit-service-btn',function() {
            //     $('#edit_id').val('');
            //     $('#edit_name').val('');
            //     $('#edit_description').val('');
            //     $('#edit_price').val('');
            //
            //     $('#update-service-modal').modal('show');
            // });

        });


    </script>
@stop
