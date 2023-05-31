<?php

namespace App\Services;
use App\Models\Sale;
use Carbon\Carbon;

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
}