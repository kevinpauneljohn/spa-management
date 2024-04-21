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
            <th>Cash Amount</th>
            <th>Non-cash Amount</th>
        </tr>
        @foreach($payments as $payment)
            <tr>
                <td>#{{$payment->sale->invoice_number}}</td>
                <td>{{$payment->payment_type}}</td>
                <td>{{number_format($payment->payment,2)}}</td>
                <td>{{number_format($payment->non_cash_payment,2)}}</td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td>Total Cash: <span class="payment">{{$totalCash}}</span></td>
            <td>Total Non Cash: <span class="payment">{{$totalNonCash}}</span></td>
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
