<html lang="">
    <head>
        <title>{{$pageTitle}}</title>
        <style media="print">

            body{
                font-family: sans-serif;
            }
            h4,h5{
                line-height: 1.6;
            }
        </style>
    </head>
    <body>
        <div style="margin:10px;">
            <center>
                <p>
                    <span style="font-weight: bold;font-size: 10pt;">{{$sale->spa->name}}</span><br/><br/>
                    <span style="font-weight: bold;font-size: 8pt;">
                    Bldg. 2 Unit 51B & 51C, Great Mall, Brgy. Tabun. Xevera Mabalacat
                    </span>
                    <br/>
                    <span style="font-weight: bold;font-size: 8pt;">
                    Contact Us: 09218173000
                    </span>
                </p>
                <span style="font-size: 9pt">
                    Date: {{$sale->paid_at}}
                </span><br/>
                <span style="font-size: 9pt">
                    Cashier: {{ucwords(\App\Models\User::find($sale->user_id)->fullname)}}
                </span>
                <hr/>
                <table width="100%">
                    <tr>
                        <th style="text-align: left; font-size: 9pt;" colspan="2">Client: {{$sale->transactions()->first()->client->full_name}}</th>
                    </tr>
                    <tr>
                        <th style="border-top: solid 4px black;text-align: left; font-size: 9pt;">Service</th>
                        <th style="border-top: solid 4px black;text-align: right;font-size: 9pt;">Amount</th>
                    </tr>
                    @foreach($sale->transactions as $transaction)
                        <tr>
                            <td style="font-size: 8pt;">{{$transaction->service_name}}</td>
                            <td style="font-size: 8pt;">{{number_format($transaction->amount,2)}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="font-weight: bold; border-top: solid 4px black;font-size: 9pt;">Total</td>
                        <td style="font-weight: bold; border-top: solid 4px black;font-size: 9pt;">{{number_format($sale->transactions->sum('amount'),2)}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;font-size: 9pt;">Cash</td>
                        <td style="text-align: right;font-size: 9pt;">{{number_format($sale->amount_paid,2)}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;font-size: 9pt;">Change</td>
                        <td style="text-align: right;font-size: 9pt;">{{number_format($sale->change,2)}}</td>
                    </tr>
                </table>

                <h4>Happy To Serve You!</h4>
            </center>
        </div>

    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            window.print()
            setTimeout(window.close, 500);
        });
    </script>
    </body>
</html>
