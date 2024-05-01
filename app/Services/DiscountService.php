<?php

namespace App\Services;

use App\Models\Discount;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class DiscountService
{
    public function checkPermissions()
    {
        $this->createViewDiscount()
            ->createAddDiscount()
            ->createEditDiscount()
            ->createDeleteDiscount();
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
                return $discount->created_at->format('M d, Y g:i:s a');
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
                return $discount->sales_id;
            })
            ->addColumn('action',function($discount){
                $action = '';
                if(auth()->user()->can('view discounts'))
                {
                    $action .= '<button class="btn btn-info btn-xs mr-1 view-bar-code" id="'.$discount->id.'">View Bar Code</button>';
                    $action .= '<button class="btn btn-success btn-xs mr-1" id="'.$discount->id.'">View</button>';
                }
                if(auth()->user()->can('edit discounts'))
                {
                    $action .= '<button class="btn btn-primary btn-xs mr-1" id="'.$discount->id.'">Edit</button>';
                }

                if(auth()->user()->can('delete discounts'))
                {
                    $action .= '<button class="btn btn-danger btn-xs mr-1" id="'.$discount->id.'">Delete</button>';
                }

                return $action;
            })
            ->rawColumns(['action','code'])
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
        return (bool)Discount::create([
            'type' => $request->input('type'),
            'is_amount' => $request->input('value_type') == "amount",
            'amount' => $request->input('value_type') == "amount" ? $request->input('amount') : null,
            'percent' => $request->input('value_type') == "percentage" ? $request->input('amount') : null,
            'client_id' => $request->input('client')
        ]);
    }
}
