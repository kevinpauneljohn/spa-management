<div class="d-flex flex-row-reverse">

    @foreach($sales as $sale)
        @if($sale->payment_status !== 'completed')
            <div>
                <a href="@if($salesInvoice === $sale->invoice_number) # @else {{route('pos.add.transaction',['spa' => $sale->spa_id,'sale' => $sale->id])}} @endif"
                   class="btn btn-default bg-white btn-flat border-bottom-0 @if($salesInvoice === $sale->invoice_number) sales-active  @else sales-tab @endif">
                    #{{$sale->invoice_number}}
                </a>
            </div>
        @endif
    @endforeach

    @if(collect($sales)->count() > 1)
            <div>
                <a href="#" class="btn btn-default bg-white btn-flat border-bottom-0 ">
                    <span class="text-bold">Pending Sales</span>
                    <i class="fa fa-arrow-right ml-2" aria-hidden="true"></i>
                </a>
            </div>
    @endif
        <div>
            <a href="{{route('point-of-sale.show',['point_of_sale' => $spa])}}" class="btn btn-default bg-white btn-flat border-bottom-0 sales-tab">
                <i class="fa fa-arrow-left ml-2" aria-hidden="true"></i>
                <span class="text-bold">Go To Dashboard</span>
            </a>
        </div>
</div>

@once
    @section('css')
        <style>
            .sales-tab{
                color: dodgerblue !important;
            }
            .sales-tab:hover{
                background-color: lightslategray !important;
                color:white !important;
            }
        </style>
    @endsection
@endonce
