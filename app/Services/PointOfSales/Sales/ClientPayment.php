<?php

namespace App\Services\PointOfSales\Sales;

use App\Models\Payment;
use App\Models\SalesShift;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Contracts\Activity;

class ClientPayment extends AmountToBePaid
{
    protected $shift_id;
    public function __construct()
    {
        $this->shift_id = $this->getSalesShift()->id;
    }
    private function getSalesShift()
    {
        return SalesShift::where('user_id',auth()->user()->id)->where('completed',false)->first();
    }

    /**
     * update the cash on drawer
     * @param $amountPaid
     * @param $change
     * @return void
     */
    private function updateCashDrawer($amountPaid, $change): void
    {
        $salesShift = SalesShift::find($this->shift_id);
        $salesShift->start_money = ($salesShift->start_money + $amountPaid) - $change;
        $salesShift->save();
    }

    /**
     * @param $paymentType
     * @param $amount
     * @param $referenceNo
     * @return void
     */
    private function saveSalesShiftPayments($paymentType, $amount, $nonCashAmount, $change, $referenceNo, $saleId): void
    {
        Payment::create([
            'sales_shift_id' => $this->shift_id,
            'sale_id' => $saleId,
            'payment' => is_null($amount) ? 0 : $amount,
            'non_cash_payment' => is_null($nonCashAmount) ? 0 : $nonCashAmount,
            'change' => $change,
            'payment_type' => $paymentType,
            'reference_number' => $referenceNo
        ]);
    }
    public function payment($salesId, $paymentType, $amount, $referenceNo, $nonCashAmount, $voucherCode = null): bool
    {
        if($paymentType === 'Cash')
        {
            return $this->cash($salesId, $paymentType, $amount);
        }
        elseif ($paymentType === 'Voucher')
        {
            return $this->voucher($salesId, $paymentType, $nonCashAmount, $amount, $voucherCode);
        }
        else{
            return $this->nonCash($salesId, $paymentType, $referenceNo, $nonCashAmount, $amount);
        }
    }


    private function voucher($salesId, $paymentType, $voucherAmount, $cashAmount, $voucherCode): bool
    {
        $cashAmount = is_null($cashAmount) ? 0 : $cashAmount;
        $voucherAmount = is_null($voucherAmount) ? 0 : $voucherAmount;
        $amount = $voucherAmount + $cashAmount;

        $amount_to_be_paid = $this->totalAmount($salesId);

        $sales = $this->sales($salesId);
        $sales->payment_method = $paymentType;
        $sales->amount_paid = $amount;
        $sales->non_cash_payment = [
            'non_cash_amount' => $voucherAmount,
            'cash_amount' => $cashAmount,
        ];
        $sales->total_amount = $amount_to_be_paid;
        $sales->change = $amount - $sales->total_amount;
        $sales->payment_status = 'completed';
        $sales->paid_at = now();
        if($sales->save())
        {
            $this->saveSalesShiftPayments($paymentType, ($amount - $sales->change), null, $sales->change, null, $sales->id);
            $this->claimVoucher($salesId, $voucherCode);
            $this->updateCashDrawer($cashAmount, 0);
            $this->activityLogs($sales, $amount);
            return true;
        }
        return false;
    }

    /**
     * once the sales transactions has been completed the voucher codes will be
     * claimed in the discounts table
     * @param $salesId
     * @param $voucherCode
     * @return void
     */
    private function claimVoucher($salesId, $voucherCode): void
    {
        DB::table('discounts')->whereIn('code', $voucherCode)->update([
            'sales_id_claimed' => $salesId, 'date_claimed' => now()
        ]);
    }


    /**
     * payment process for cash payment type
     * @param $salesId
     * @param $paymentType
     * @param $amount
     * @return bool
     */
    private function cash($salesId, $paymentType, $amount): bool
    {
        if($this->totalAmount($salesId) <= $amount)
        {
            $sum = $this->totalAmount($salesId);

            $sales = $this->sales($salesId);
            $sales->payment_method = $paymentType;
            $sales->amount_paid = $amount;
            $sales->total_amount = $sum;
            $sales->change = $amount - $sum;
            $sales->payment_status = 'completed';
            $sales->paid_at = now();

            if($sales->save())
            {
                $this->saveSalesShiftPayments($paymentType, ($amount - $sales->change), null, $sales->change, null, $sales->id);
                $this->updateCashDrawer($sales->amount_paid, $sales->change);
                $this->activityLogs($sales, $amount);
                return true;
            }
        }
        return false;
    }

    /**
     * payment saving for non-cash payment type
     * @param $salesId
     * @param $paymentType
     * @param $referenceNo
     * @return bool
     */
    private function nonCash($salesId, $paymentType, $referenceNo, $nonCashAmount, $cashAmount): bool
    {
        $cashAmount = is_null($cashAmount) ? 0 : $cashAmount;
        $nonCashAmount = is_null($nonCashAmount) ? 0 : $nonCashAmount;
        $amount = $nonCashAmount + $cashAmount;

        $amount_to_be_paid = $this->totalAmount($salesId);
        if(!is_null($referenceNo) && !$this->isNonCashAmountExceeded($nonCashAmount, $amount_to_be_paid))
        {
            $sales = $this->sales($salesId);
            $sales->payment_method = $paymentType;
            $sales->amount_paid = $amount;
            $sales->non_cash_payment = [
                'non_cash_amount' => $nonCashAmount,
                'cash_amount' => $cashAmount
            ];
            $sales->total_amount = $amount_to_be_paid;
            $sales->change = $amount - $sales->total_amount;
            $sales->payment_status = 'completed';
            $sales->payment_account_number = $referenceNo;
            $sales->paid_at = now();

            if($sales->save())
            {
                $this->saveSalesShiftPayments($paymentType, ($cashAmount - $sales->change), $nonCashAmount, $sales->change, $referenceNo, $sales->id);
                $this->updateCashDrawer($cashAmount, $sales->change);
                $this->activityLogs($sales, 0);
                return true;
            }
        }
        return false;
    }

    private function isNonCashAmountExceeded($nonCashAmount, $amount_to_be_paid): bool
    {
        return $nonCashAmount > $amount_to_be_paid;
    }

    /**
     * this will log the action taken by the authenticated user
     * @param $sales
     * @param $client_cash
     * @return void
     */
    private function activityLogs($sales, $client_cash)
    {
        activity('sales_payment')->causedBy(auth()->user()->id)
            ->withProperties(collect($sales)->merge([
                'client_cash' => $client_cash,
                'caused_by' => auth()->user()->id,
                'causer_name' => auth()->user()->fullname,
                'transactions' => collect($sales->transactions)->toArray(),
                'total_amount' => $this->totalAmount($sales->id)
            ]))
            ->tap(function(Activity $activity) use ($sales){
                $activity->spa_id = $sales->spa_id;
            })
            ->log('Sales Payment');
    }
}
