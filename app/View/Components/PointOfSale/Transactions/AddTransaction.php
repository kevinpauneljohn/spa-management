<?php

namespace App\View\Components\PointOfSale\Transactions;

use App\Models\Sale;
use Illuminate\View\Component;

class AddTransaction extends Component
{
    public $salesId;
    public $display;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($salesId)
    {
        $this->salesId = $salesId;
        $this->display = Sale::find($salesId)->payment_status === 'completed';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.point-of-sale.transactions.add-transaction');
    }
}
