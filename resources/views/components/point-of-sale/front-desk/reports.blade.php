<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Shift #</th>
                <th>Cash On Hand</th>
                <th>Cash</th>
                <th>Non-Cash</th>
                <th>Gross Sales</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesShifts as $shift)
                <tr>
                    <td>{{$shift->created_at->format('M-d-Y h:m a')}}</td>
                    <td class="text-blue">#{{$shift->id}}</td>
                    <td class="text-green">{{number_format($shift->start_money,2)}}</td>
                    <td class="text-green">{{number_format($shift->totalCash($shift->id),2)}}</td>
                    <td class="text-fuchsia">{{number_format($shift->totalNonCash($shift->id),2)}}</td>
                    <td class="text-blue text-bold">{{
                            number_format(
                                ($shift->totalCash($shift->id) + $shift->totalNonCash($shift->id))
                            ,2)
                        }}
                    </td>
                    <td>
                        <a href="{{route('print-shift-sales',['shiftId' => $shift->id])}}" class="btn btn-success" target="_blank">Print</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
