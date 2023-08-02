<?php

namespace App\View\Components\PointOfSale;

use Illuminate\View\Component;

class AddSales extends Component
{
    public $spa;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spa)
    {
        $this->spa = $spa;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.point-of-sale.add-sales');
    }
}
