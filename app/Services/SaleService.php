<?php

namespace App\Services;
use App\Models\Sale;
use App\Models\SalesShift;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class SaleService
{
    public function create($data)
    {
        $sale = Sale::create([
            'spa_id' => $data['spa_id'],
            'amount_paid' => $data['amount_paid'],
            'payment_status' => $data['payment_status'],
            'user_id' => $data['user_id'],
            'appointment_batch' => $data['appointment_batch'],
            'payment_method' => $data['payment_method'],
            'payment_account_number' => $data['payment_account_number'],
            'payment_bank_name' => $data['payment_bank_name']
        ]);

        $status = false;
        $data = [];
        if ($sale) {
            $status = true;
            $data = $sale;    
        }

        $response = [
            'status' => $status,
            'data' => $data,
        ]; 

        return $response;
    }

    public function update($request, $id)
    {
        $now = Carbon::now()->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $sale = Sale::findOrFail($id);

        $sale->payment_status = $request->payment_status;
        $sale->payment_method = $request->payment_method;
        $sale->payment_account_number = $request->payment_account_number;
        $sale->payment_bank_name = $request->payment_bank_name;
        $sale->user_id = auth()->user()->id;
        if ($request->payment_status == 'paid') {
            $sale->paid_at = $now;
        }
        
        $status = false;
        $message = 'Unable to update sales. Please try again.';
        if ($sale->save()) {
            $status = true;
            $message = 'Sales has been updated.';
        }

        $response = [
            'status'   => $status,
            'message'   => $message
        ]; 

        return $response;
    }

    public function end_of_shift_report($spa_id, $shift_id)
    {
        $salesShift = SalesShift::findOrFail($shift_id);
        $start_shift = date('h:i:s A', strtotime($salesShift->start_shift));
        $end_shift = date('h:i:s A', strtotime($salesShift->end_shift));

        $shift_data = [
            'start_shift' => $start_shift,
            'end_shift' => $end_shift,
            'date_start' => date('F d, Y', strtotime($salesShift->start_shift)),
            'date_end' => date('F d, Y', strtotime($salesShift->end_shift)),
            'start_money' => number_format($salesShift->start_money, 2, '.', ','),
        ];

        $sale = Sale::where([
            'spa_id' => $spa_id, 
            'user_id' => auth()->user()->id,  
            'payment_status' => 'paid', 
        ])->whereBetween('paid_at', [$salesShift->start_shift, $salesShift->end_shift])->get();

        $total_sale = 0;
        $data = [];
        if (!empty($sale)) {
            foreach ($sale as $sales) {
                $data [] = [
                    'invoice' => 'Invoice # '.substr($sales->id, -6),
                    'payment_method' => ucfirst($sales->payment_method),
                    'reference_number' => $sales->payment_account_number ? $sales->payment_account_number : 'N/A',
                    'payment_date' => date('F d, Y h:i:s A', strtotime($sales->paid_at)),
                    'subtotal' => '&#8369; '.number_format($sales->amount_paid, 2, '.', ','),
                ];

                $total_sale+= number_format($sales->amount_paid, 2, '.', ',');
            }
        }

        $total_sales_plus_start_money = $total_sale + $salesShift->start_money;
        $response = [
            'data'   => $data,
            'shift_data' => $shift_data,
            'total_sales' => number_format($total_sale, 2, '.', ','),
            'total_sales_plus_start_money' => number_format($total_sales_plus_start_money, 2, '.', ',')
        ]; 

        return $response;
    }
}