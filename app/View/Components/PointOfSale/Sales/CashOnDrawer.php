<?php

namespace App\View\Components\PointOfSale\Sales;

use App\Models\SalesShift;
use Illuminate\View\Component;

class CashOnDrawer extends Component
{
    public $cashOnDrawer;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spaId)
    {
        $this->cashOnDrawer = auth()->user()->hasAnyRole(['manager','front desk']) ? SalesShift::where('user_id',auth()->user()->id)
            ->where('spa_id',$spaId)->where('end_shift',null)
            ->where('completed',false)->first()->start_money : 0;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.point-of-sale.sales.cash-on-drawer');
    }
}
