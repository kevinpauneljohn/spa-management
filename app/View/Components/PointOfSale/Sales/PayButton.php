<?php

namespace App\View\Components\PointOfSale\Sales;

use App\Models\Sale;
use Illuminate\View\Component;

class PayButton extends Component
{
    public $spaId;
    public $salesId;
    public $display;
    public $sales;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spaId, $salesId)
    {
        $this->spaId = $spaId;
        $this->salesId = $salesId;

        $this->sales = Sale::find($salesId);
        $this->display = $this->sales->payment_status === 'pending' && $this->sales->transactions->count() > 0;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.point-of-sale.sales.pay-button');
    }
}
