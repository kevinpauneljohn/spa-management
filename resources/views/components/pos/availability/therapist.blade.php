<input type="hidden" class="form-control spaId" value="{{$spaId}}">
<div class="card">
    <div class="card-header bg-light">
        <h3 class="card-title">
            <i class="fas fa-users"></i>
            Masseur Availability
        </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool collapsed" data-toggle="collapse" href="#collapse-collapsed" aria-expanded="true" aria-controls="collapse-collapsed" >
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div id="collapse-collapsed" class="collapse">
        <div class="card-body">
            <div class="tab-content p-0">
                <div class="progress-group availableMasseur">

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
        <script src="{{asset('js/frontdesk/TherapistAvailabilityComponent/therapistAvailabiltyFunction.js')}}"></script>
        <script>
            getMasseurAvailability($('.spaId').val());
        </script>
    @endif
@endpush
