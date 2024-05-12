<?php

namespace App\View\Components\PointOfSale\Transactions;

use App\Models\Discount;
use Illuminate\View\Component;

class BuyVoucher extends Component
{
    public $salesId;
    public $vouchers;
    public $tableId;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($salesId, $tableId)
    {
        $this->salesId = $salesId;
        $this->tableId = $tableId;
        $this->vouchers = Discount::where('type','voucher')->where('sale_id',null)->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.point-of-sale.transactions.buy-voucher');
    }
}
