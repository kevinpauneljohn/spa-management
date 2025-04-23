<?php

namespace App\Services;

use App\Models\Discount;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class DiscountService
{
    public function checkPermissions()
    {
        $this->createAccessDiscounts()
            ->createViewDiscount()
            ->createAddDiscount()
            ->createEditDiscount()
            ->createDeleteDiscount();
    }

    private function createAccessDiscounts(): DiscountService
    {
        if(Permission::where('name','access discounts')->count() == 0)
        {
            Permission::create(['name' => 'access discounts'])->syncRoles(['owner']);
        }
        return $this;
    }

    private function createViewDiscount(): DiscountService
    {
        if(Permission::where('name','view discounts')->count() == 0)
        {
            Permission::create(['name' => 'view discounts'])->syncRoles(['owner','front desk','manager']);
        }
        return $this;
    }

    private function createAddDiscount(): DiscountService
    {
        if(Permission::where('name','add discounts')->count() == 0)
        {
            Permission::create(['name' => 'add discounts'])->syncRoles(['owner']);
        }
        return $this;
    }

    private function createEditDiscount(): DiscountService
    {
        if(Permission::where('name','edit discounts')->count() == 0)
        {
            Permission::create(['name' => 'edit discounts'])->syncRoles(['owner']);
        }
        return $this;
    }

    private function createDeleteDiscount(): void
    {
        if(Permission::where('name','delete discounts')->count() == 0)
        {
            Permission::create(['name' => 'delete discounts'])->syncRoles(['owner']);
        }
    }

    public function discountTable($query)
    {
        return DataTables::of($query)
            ->editColumn('created_at',function($discount){
                return $discount->created_at->format('m/d/Y g:i:s a');
            })
            ->editColumn('title',function($discount){
                return ucwords($discount->title);
            })
            ->editColumn('code',function($discount){
                return $discount->code;
            })
            ->editColumn('client_id',function($discount){
                return !is_null($discount->client_id) ? ucwords(strtolower($discount->client->full_name)) : '';
            })
            ->addColumn('discount_amount',function($discount){
                $unit = !$discount->is_amount ? ' %':'';
                return $discount->discount_amount.$unit;
            })
            ->addColumn('sales_invoice',function($discount){
                if(is_null($discount->sale))
                {
                    return '';
                }
//                return '<a href="'.route('pos.add.transaction',['spa' => $discount->sale->spa->id, 'sale' => $discount->sale->id]).'" class="text-primary" target="_blank">#'.$discount->sale->invoice_number.'</a>';
                return '';
            })
            ->addColumn('sales_invoice_claimed',function($discount){
                if(is_null($discount->sales_id_claimed))
                {
                    return '';
                }
                return is_null($discount->sales_claiming) ? 'yes' : $discount->sales_claiming->spa_id;
//                return '<a href="'.route('pos.add.transaction',['spa' => $discount->sales_claiming->spa->id, 'sale' => $discount->sales_claiming->id]).'" class="text-primary" target="_blank">#'.$discount->invoice_number_claimed.'</a>';
            })
            ->addColumn('action',function($discount){
                $action = '';
                if(auth()->user()->can('view discounts'))
                {
                    $action .= '<button class="btn btn-info btn-xs mr-1 view-bar-code" id="'.$discount->id.'">View Bar Code</button>';
//                    $action .= '<button class="btn btn-success btn-xs mr-1" id="'.$discount->id.'">View</button>';
                }
//                if(auth()->user()->can('edit discounts'))
//                {
//                    $action .= '<button class="btn btn-primary btn-xs mr-1" id="'.$discount->id.'">Edit</button>';
//                }

                if(auth()->user()->can('delete discounts') )
                {
                    if(is_null($discount->sales_id_claimed) && is_null($discount->sale_id))
                    {
                        $action .= '<button class="btn btn-danger btn-xs mr-1 delete-discount" id="'.$discount->id.'">Delete</button>';
                    }

                }

                return $action;
            })
            ->rawColumns(['action','code','sales_invoice','sales_invoice_claimed'])
            ->make(true);
    }

    public function generateBarCode($code): string
    {
        return (new DNS1D())->getBarcodeHTML($code, 'C39');
    }

    public function generateQrCode($code): string
    {
        return (new DNS2D())->getBarcodeHTML($code, 'QRCODE');
    }

    public function saveDiscount($request): bool
    {
        $quantity = $request->input('quantity');

        for ($voucher = 1; $voucher <= $quantity; $voucher++){
            Discount::create([
                'title' => $request->input('title'),
                'type' => $request->input('type'),
                'is_amount' => $request->input('value_type') == "amount",
                'amount' => $request->input('value_type') == "amount" ? $request->input('amount') : null,
                'percent' => $request->input('value_type') == "percentage" ? $request->input('amount') : null,
                'price' => $request->input('price'),
                'client_id' => $request->input('client')
            ]);
        }
        return true;
    }

    public function removeVoucher($voucherId): bool
    {
        return (bool)DB::table('discounts')->where('id',$voucherId)->update(['sale_id' => null,'payment_status' => null]);
    }

    public function getDiscountByCode($code)
    {
        $discount = Discount::where('code',$code);
        if($discount->count() < 1)
        {
            return ['success' => false, 'message' => 'Voucher does not exist!'];
        }
        if($discount->where('payment_status','completed')->count() > 0)
        {
            if($discount->where('date_claimed',null)->count() > 0)
            {
                return $discount->first();
            }
            return ['success' => false, 'message' => 'Voucher was already claimed!'];
        }
        return ['success' => false, 'message' => 'Voucher was not paid!'];

    }

    public function checkVoucherAvailability($code)
    {
        return Discount::where('code',$code)->where('sale_id',null)->where('date_claimed',null)->first();
    }

    public function getCouponByCode($code)
    {
        return Discount::where('code',$code)->where('type','coupon')->where('date_claimed',null)->where('sales_id_claimed',null)->first();
    }

}
