<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{asset('vendor/adminlte/dist/img/user2-160x160.jpg')}}" alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center">{{$therapist->fullname}}</h3>

                        <p class="text-muted text-center">@if($therapist->gender === 'male') Masseur @else Masseuse @endif</p>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- About Me Box -->
                <div class="card card-olive personal-information">
                    <div class="card-header">
                        <h3 class="card-title">Personal Information</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <strong><i class="fa fa-birthday-cake mr-1"></i> Date Of Birth</strong>

                        <p class="text-muted">{{$therapist->user->date_of_birth}}</p>

                        <hr>

                        <strong><i class="fas fa-envelope mr-1"></i> Email</strong>

                        <p class="text-muted">{{$therapist->user->email}}</p>

                        <hr>

                        <strong><i class="fas fa-phone-alt mr-1"></i> Contact Number</strong>

                        <p class="text-muted">{{$therapist->user->mobile_number}}</p>

                        <hr>

                        <strong><i class="fas fa-restroom mr-1"></i> Gender</strong>

                        <p class="text-muted">{{$therapist->gender}}</p>
                        <hr>

                        <strong><i class="far fa-file-alt mr-1"></i> Certificate</strong>

                        <p class="text-muted">{{$therapist->certificate}}</p>
                        <hr>

                        @if(auth()->user()->hasRole(['super admin','owner']))
                            <strong><i class="fas fa-hand-holding-usd mr-1"></i> Offer type</strong>

                            <p class="text-muted">{{$therapist->offer_type}}</p>
                            <hr>

                            <strong><i class="fas fa-percentage mr-1"></i> Commission Rate</strong>

                            <p class="text-muted">{{$therapist->commission_percentage}}</p>
                            <hr>

                            <strong><i class="fas fa-money-bill mr-1"></i> Commission Amount</strong>

                            <p class="text-muted">{{$therapist->commission_flat}}</p>
                            <hr>

                            <strong><i class="fas fa-money-bill-alt mr-1"></i> Allowance</strong>

                            <p class="text-muted">{{$therapist->allowance}}</p>
                        @endif

                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card main-therapist-content">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#performance" data-toggle="tab">Performance</a></li>
                            <li class="nav-item"><a class="nav-link" href="#clients" data-toggle="tab">Clients</a></li>
                            <li class="nav-item"><a class="nav-link" href="#account-details" data-toggle="tab">Account Details</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="performance">
                                activity
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="clients">
                                timeline here
                            </div>
                            <!-- /.tab-pane -->

                            <div class="tab-pane" id="account-details">
{{--                                <x-therapist-form spaId="{{$therapist->spa_id}}" :therapist="$therapist ??''" type="specific-spa"/>--}}
                                <x-forms.therapist-form spaId="{{$therapist->spa_id}}" :therapist="$therapist ??''"/>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@section('plugins.CustomCSS',true)

