<?php

namespace App\View\Components\PointOfSale\Transactions;

use Illuminate\View\Component;

class AddClientForm extends Component
{
    public $spa;
    public $sale;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spa, $sale)
    {
        $this->spa = $spa;
        $this->sale = $sale;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.point-of-sale.transactions.add-client-form');
    }
}
