<?php

namespace App\View\Components\PointOfSale\Sales;

use App\Models\Sale;
use Illuminate\View\Component;

class PrintInvoice extends Component
{
    public $salesId;
    public $sales;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($salesId)
    {
        $this->sales = Sale::find($salesId);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.point-of-sale.sales.print-invoice');
    }
}
