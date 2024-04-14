<html>
<head>
    <title>Print Shift Sales</title>
    <style>
        table, p{
            width: 100%;
            font-size: 10pt;
        }
        table, th, td{
            border:solid 1px black;
            border-collapse: collapse;
        }
        th, td{
            padding: 5px;
        }
        .payment{
            font-weight: bold;
        }
    </style>
</head>
<body>
<table>
    <tr>
        <td>
            Cashier: <span class="payment">{{auth()->user()->fullname}}</span>
        </td>
        <td>
            Shift:
            <span class="payment">{{$salesShift->created_at->format('M-d-y h:m a')}}</span> -
            <span class="payment">{{\Carbon\Carbon::parse($salesShift->end_shift)->format('M-d-y h:m a')}}</span>
        </td>
        <td>
            Money on hand: <span class="payment">{{number_format($salesShift->start_money,2)}}</span>
        </td>
    </tr>
</table>
    <table>
        <tr>
            <th>Invoice</th>
            <th>Payment Type</th>
            <th>Amount</th>
        </tr>
        @php
            $totalCash = 0;
            $totalNonCash = 0;
        @endphp
        @foreach($payments as $payment)
            @php
                if($payment->payment_type == 'Cash')
                {
                    $totalCash = $totalCash + $payment->sale->total_amount;
                }
                else{
                    $totalNonCash = $totalNonCash + $payment->payment;
                }
            @endphp
            <tr>
                <td>#{{$payment->sale->invoice_number}}</td>
                <td>{{$payment->payment_type}}</td>
                <td>{{$payment->payment_type == 'Cash' ? number_format($payment->sale->total_amount,2) : number_format($payment->payment,2)}}</td>
            </tr>
        @endforeach
        <tr>
            <td>Total Cash: <span class="payment">{{number_format($totalCash,2)}}</span></td>
            <td>Total Non Cash: <span class="payment">{{number_format($totalNonCash,2)}}</span></td>
            <td>Gross Sales: <span class="payment">{{ number_format(($totalCash + $totalNonCash),2)  }}</span></td>
        </tr>
    </table>

    <p style="margin-top: 30px;">
        <span>Witnessed By:</span>
    </p>
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        window.print()
        setTimeout(window.close, 500);
    });
</script>
</body>
</html>
