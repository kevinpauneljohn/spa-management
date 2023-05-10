@extends('adminlte::page')

@section('title', 'Services')

@section('content_header')
    <h1></h1>
@stop
<style>
    .required {
        color: red;
    }
    .hidden{
        display:none;
    }
    .hiddenBtn {
        display: none !important;
    }
    .select2-container--default .select2-selection--single {
        height: 35px !important;
    }
</style>
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Services</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('owners.index')}}">Owners</a></li>
                        <li class="breadcrumb-item"><a href="{{route('spa.overview',['id' => $owners->id])}}">Spa</a> </li>
                        <li class="breadcrumb-item active">Services</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="callout callout-info">
        <h5><i class="fas fa-info"></i> Note:</h5>
        Create the services you offer to your customers.
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <i class="fas fa-spa profile-user-img img-fluid img-circle" style="width:80px;height:80px;font-size:60px;"></i>
                    </div>

                    <h3 class="profile-username text-center">{{ucfirst($spa['name'])}}</h3>
                    <input type="hidden" class="form-control spa-id" value="{{$spa['id']}}" />
                    <p class="text-muted text-center">Spa</p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Address</b> <a class="float-right">{{ucfirst($spa['address'])}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Rooms </b> <a class="float-right" href="#">{{$spa['number_of_rooms']}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>License: </b> <a class="float-right" href="#">{{$spa['license']}}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#data" data-toggle="tab">List</a></li>
                        <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="data">
                            <div class="row">
                                <div class="col-md-12">
                                    @can('add service')
                                        <button type="button" class="btn bg-gradient-primary btn-sm float-right" id="addNewService"><i class="fa fa-plus-circle"></i> Add New</button>
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
                                            <th>Duration</th>
                                            <th>Category</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="settings">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('add service')
        <div class="modal fade" id="add-new-service-modal">
            <form role="form" id="service-form" class="form-submit">
                @csrf
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">New Services Form</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="bs-stepper" id="bs-stepper-add">
                                <div class="bs-stepper-header" role="tablist">
                                    <div class="step" data-target="#info-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="info-part" id="info-part-trigger">
                                            <span class="bs-stepper-circle">1</span>
                                            <span class="bs-stepper-label">Info</span>
                                        </button>
                                    </div>
                                    <div class="line"></div>
                                    <div class="step" data-target="#price-part">
                                        <button type="button" class="step-trigger" role="tab" aria-controls="price-part" id="price-part-trigger">
                                            <span class="bs-stepper-circle">2</span>
                                            <span class="bs-stepper-label">Pricing</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="bs-stepper-content">
                                    <div id="info-part" class="content" role="tabpanel" aria-labelledby="info-part-trigger">
                                        <div class="form-group name">
                                            <label for="name">Name</label><span class="required">*</span>
                                            <input type="text" name="name" class="form-control" id="name">
                                        </div>
                                        <div class="form-group description">
                                            <label for="description">Description</label><span class="required">*</span>
                                            <input type="text" name="description" class="form-control" id="description">
                                        </div>
                                    </div>
                                    <div id="price-part" class="content" role="tabpanel" aria-labelledby="price-part-trigger">
                                        <div class="form-group duration">
                                            <label for="duration">Duration</label><span class="required">*</span>
                                            <br />
                                            <select class="form-control duration-select" name="duration" id="duration" style="width:100%;">
                                                <option value="">Select here</option>
                                                @foreach($range as $key => $data)
                                                    <option value="{{$data}}">{{$data}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group price">
                                            <label for="price">Price</label>
                                            <input type="number" class="form-control" id="price" name="price">
                                        </div>
                                        <div class="form-group price_per_plus_time">
                                            <label for="price_per_plus_time">Plus time price every 15 minutes</label>
                                            <input type="number" class="form-control" id="price_per_plus_time" name="price_per_plus_time">
                                        </div>
                                        <div class="form-group category">
                                            <label for="category">Category</label>
                                            <select name="category" class="form-control" id="category">
                                                <option value="">Select here</option>
                                                <option value="regular">Regular</option>
                                                <option value="promo">Promo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default closeModal" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary info_next_btn" onclick="stepper.next()" disabled>Next</button>
                            <button type="button" class="btn btn-default price_previous_btn hiddenBtn" onclick="stepper.previous()">Previous</button>
                            <button type="button" class="btn btn-primary price_submit_btn add-service-btn hiddenBtn" disabled>Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
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
                                            <label for="edit_price_per_plus_time">Plsu time price every 15 minutes</label>
                                            <input type="number" class="form-control" id="edit_price_per_plus_time" name="edit_price_per_plus_time">
                                        </div>
                                        <div class="form-group edit_category">
                                            <label for="edit_category">Category</label>
                                            <select name="edit_category" class="form-control" id="edit_category">

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default edit_closeModal" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary edit_info_next_btn" onclick="steppers.next()">Next</button>
                            <button type="button" class="btn btn-default edit_price_previous_btn hiddenBtn" onclick="steppers.previous()">Previous</button>
                            <button type="button" class="btn btn-primary edit_price_submit_btn update-service-btn hiddenBtn">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endcan
@stop

@section('css')
@stop

@section('js')
<script src="{{asset('js/service.js')}}"></script>
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
                    { data: 'duration', name: 'duration'},
                    { data: 'category', name: 'category'},
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                ],
                responsive:true,
                order:[0,'desc'],
                pageLength: 50
            });

            $('#addNewService').on('click', function() {
                $('#name').val('');
                $('#description').val('');
                $('#duration').val('');
                $('#price').val('');
                $('#category').val('');
                $('#add-new-service-modal').modal('show');
            });

            $(document).on('click','.edit-service-btn',function() {
                $('#edit_id').val('');
                $('#edit_name').val('');
                $('#edit_description').val('');
                $('#edit_price').val('');

                $('#update-service-modal').modal('show');
            });
        });

        function reloadServiceTable ()
        {
            var table = $('#service-list').DataTable();
            table.ajax.reload();
        }

        document.addEventListener('DOMContentLoaded', function () {
            window.stepper = new Stepper(document.querySelector('#bs-stepper-add'))
        });

        document.addEventListener('DOMContentLoaded', function () {
            window.steppers = new Stepper(document.querySelector('#bs-stepper-update'))
        });
    </script>
@stop
