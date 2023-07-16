<input type="hidden" class="form-control spaId" value="{{$spaId}}">
<div class="card">
    <div class="card-header bg-light">
        <h3 class="card-title">
            <i class="fas fa-users"></i>
            Upcoming Appointments
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-toggle="collapse" href="#upcoming-collapsed" aria-expanded="true" aria-controls="upcoming-collapsed" >
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div id="upcoming-collapsed" class="collapse">
        <div class="card-body">
            <div class="tab-content p-0">
                <div class="progress-group upcomingGuest">

                </div>
            </div>
        </div>
    </div>
</div>
@push('css')
    <style>

    </style>
@endpush

@push('js')
    @if(auth()->check())
        <script src="{{asset('js/frontdesk/UpcomingAppointmentComponent/upComingGuestFunction.js')}}"></script>
        <script>
            getUpcomingGuest($('.spaId').val());
        </script>
    @endif
@endpush

