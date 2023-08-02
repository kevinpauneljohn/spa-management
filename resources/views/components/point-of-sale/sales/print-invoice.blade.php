<span id="print-invoice-section">
    @if($sales->transactions->count() > 0 && $sales->payment_status === 'completed')
        <a href="{{route('print-invoice',['sale' => $sales->id])}}" target="_blank" class="btn btn-default print-invoice" data-bs-toggle="tooltip" data-placement="left" title="Print Invoice"><i class="fa fa-print" aria-hidden="true"></i></a>
    @endif
</span>

@once
    @push('js')
        <script>
            $(document).ready(function(){
                $('.print-invoice').tooltip()
            });
        </script>
    @endpush
@endonce
