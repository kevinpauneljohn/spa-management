<input type="hidden" class="form-control spaId" value="{{$spaId}}">
<div class="modal fade" id="start-shift-modal"  data-keyboard="false" data-backdrop="static">
    <form role="form" id="start-shift-form" class="form-submit modal-dialog-centered">
        @csrf
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Good Day {{ucfirst(auth()->user()->firstname)}}!</h4>
                </div>
                <div class="modal-body">
                    <h5 class="text-center shiftMessage"></h5>
                    <span class="badge badge-info text-default pointer viewEndShiftReport">View Report</span>
                </div>
                <div class="modal-footer">
                    <button id="btnStartShift" class="btn btn-primary btnStartShift mx-auto">Click here to start your shift</button>
                    <button id="btnEndShift" class="btn btn-info btnEndShift mx-auto hidden">Click here to end your shift</button>
                </div>
            </div>
        </div>
    </form>
</div>
@push('css')
    <style>

    </style>
@endpush

@push('js')
    @if(auth()->check())
        <script src="{{asset('js/frontdesk/ShiftsComponent/action.js')}}"></script>
        <script src="{{asset('js/frontdesk/ShiftsComponent/app.js')}}"></script>
        <script>
            getPosShift($('.spaId').val());
        </script>
    @endif
@endpush