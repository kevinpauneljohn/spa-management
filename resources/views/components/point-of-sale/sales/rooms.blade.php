
<div class="row" id="room-availability-section">
    @for($room = 1; $room <= $rooms; $room++)
        <div class="col-xl-4 col-lg-6 col-sm-6 col-6 room-holder">
            <div class="card collapsed-card @if(collect($takenRoom)->contains($room)) card-secondary @else card-success @endif">
                <div class="card-header">
                    <h3 class="card-title">Room #{{$room}}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm @if(collect($takenRoom)->contains($room)) btn-secondary @else btn-success @endif" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body collapse @if(collect($takenRoom)->contains($room)) room-taken-bg-color @else room-available-bg-color @endif">
                    @if(collect($takenRoom)->contains($room))
                        @if($transactions->where('room_id',$room)->count() > 0)
                            @php $details = $transactions->where('room_id',$room)->first(); @endphp
                            <div>

                                Invoice #: <p class="text-primary"><a href="/point-of-sale/add-transaction/{{$details->spa_id}}/{{$details->sales_id}}?view={{$details->id}}">{{$details->sale->invoice_number}}</a></p>
                            </div>
                            <div>
                                Client: <p class="text-primary">{{$details->client->full_name}}</p>
                            </div>
                            <div>
                                Start: <p class="text-primary">{{$details->startDate}}</p>
                            </div>
                            <div>
                                End: <p class="text-primary">{{$details->endDate}}</p>
                            </div>
                        @endif
                    @else
                        available
                    @endif
                </div>
            </div>
        </div>
    @endfor
</div>
