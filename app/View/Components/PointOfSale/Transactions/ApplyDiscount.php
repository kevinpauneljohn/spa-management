<?php

namespace App\View\Components\PointOfSale\Transactions;

use Illuminate\View\Component;

class ApplyDiscount extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.point-of-sale.transactions.apply-discount');
    }
}
