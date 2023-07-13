<input type="hidden" class="form-control spaId" value="{{$spaId}}">
<div class="alert alert-primary alert-dismissible">
    <h5><i class="icon fas fa-info"></i> Note:</h5>
    Green color means available, Gray color means occupied.
</div>
<div class="row displayRoomData">

</div>
@push('css')
    <style>

    </style>
@endpush

@push('js')
    @if(auth()->check())
        <script src="{{asset('js/frontdesk/BookAppointmentComponent/roomAvailabilityFunction.js')}}"></script>
        <script>
            loadRoomAvailability($('.spaId').val());
        </script>
    @endif
@endpush
