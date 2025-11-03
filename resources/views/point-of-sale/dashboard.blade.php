@extends('adminlte::page')

@section('title', $pageTitle)

@section('content_header')

@stop
<style>

</style>
@section('content')
    <div class="row mb-2">
        <div class="col-sm-6 mt-3">
            <h3 class="text-cyan">{{ucwords($spa->name)}}</h3>
        </div>
        <div class="col-sm-6 mt-3">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('owner.my.spas')}}">@if(!is_null($spa->category)) {{ucwords($spa->category)}} @endif</a> </li>
                <li class="breadcrumb-item active">{{ucwords($spa->name)}} </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-info card-outline">
                <div class="card-body">
                    @if(in_array('owner',collect(auth()->user()->getRoleNames())->toArray()) ||
        in_array('manager',collect(auth()->user()->getRoleNames())->toArray()))
                        <a href="{{route('sales-activity-logs',['spaId' => $spa->id])}}" class="btn btn-default btn-xs">
                            <i class="fa fa-list-ol mr-1" aria-hidden="true"></i>
                            Activity Logs
                        </a>
                    @else

                    @endif

                    @if(auth()->user()->hasRole(['front desk']))
                        <x-point-of-sale.sales.end-shift-button :spaId="$spa->id"/>
                    @endif

                    <div class="float-right">
                        <x-clock.digital-clock />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-7">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <div class="float-left"><h4 class="text-muted card-title">Sales Management</h4></div>


                                <div class="float-right">
                                    <x-service.menu-button title="Service Menu" :spa="$spa"/>
                                    @if(auth()->user()->hasRole(['front desk']))
                                    <x-point-of-sale.add-sales :spa="$spa"/>
                                    @endif
                                </div>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <x-point-of-sale.sales.date-range :spaId="$spa->id"/>
                                </div>
                                <div class="col-lg-4">
                                    <x-point-of-sale.sales.cash-on-drawer :spaId="$spa->id"/>
                                </div>
                            </div>
                            <div class="table-wrapper table-responsive">
                                <x-point-of-sale.sales.sales-table :spaId="$spa->id"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="float-left"><h4 class="text-muted card-title">Room Monitoring</h4></div>
                        </div>
                        <div class="card-body">
                            <x-point-of-sale.sales.rooms :spaId="$spa->id"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <div class="float-left">
                                <h3 class="text-muted">Bookings</h3>
                            </div>
                            <div class="d-flex flex-row justify-content-end">
                              <span class="mr-2">
                                <i class="fas fa-square text-success"></i> Completed
                              </span>

                                <span>
                                    <i class="fas fa-square"  style="color: #ff2f25"></i> Upcoming
                                </span>
                            </div>
                        </div>
                        <div class="card-body">

                            <x-clients.client-calendar :spaId="$spa->id" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row masseur-availability">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header bg-gradient-info">
                            <h3 class="card-title">Therapist Availability</h3>
                        </div>
                        <div class="card-body">
                            <x-point-of-sale.sales.masseur-availability :spaId="$spa->id" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('plugins.CustomCSS',true)
@section('css')
@stop

@once
    @push('js')
        @if($availableRooms < $spa->number_of_rooms)
            <script>
                let takenHolder = 0;
                $(document).ready(function(){
                    checkAvailability();
                });

                const checkAvailability = () => {
                    $.ajax({
                        url: '/room-availability-dashboard-update/{{$spa->id}}',
                    }).done(function(rooms){
                        // console.log(rooms);

                        if(rooms.taken.length > 0)
                        {
                            // console.log('taken holder = '+takenHolder)
                            // console.log('room taken = '+rooms.taken.length)
                            if(takenHolder > rooms.taken.length)
                            {
                                $('#dashboard-sales-table-list').DataTable().ajax.reload(null, false);
                                $('#room-availability-section').load('{{url()->current()}} #room-availability-section .room-holder');
                                $('.progress-transaction-details').text('');
                                // console.log('something changed!')
                            }
                            setTimeout(function(){
                                checkAvailability();
                                therapistsAvailability()
                            },5000)
                        }else{
                            $('#dashboard-sales-table-list').DataTable().ajax.reload(null, false);
                            $('#room-availability-section').load('{{url()->current()}} #room-availability-section .room-holder');
                            $('.progress-transaction-details').text('');
                        }
                        takenHolder = rooms.taken.length;
                    });
                };

            </script>
        @endif
        <script>
            $(document).on('click','#replace-btn',function(){

            })
        </script>
    @endpush
@endonce
