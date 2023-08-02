<?php

namespace App\View\Components\PointOfSale\Sales;

use App\Models\Spa;
use App\Services\UserService;
use Illuminate\View\Component;

class SalesTab extends Component
{
    public $spa;
    public $sales;
    public $salesInvoice;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spa, UserService $userService, $salesInvoice = null)
    {
        $this->spa = $spa;
        $this->sales = $userService->get_staff_owner()
            ->spas()->where('id','=',$spa)->first()->sales;
        $this->salesInvoice = $salesInvoice;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.point-of-sale.sales.sales-tab');
    }
}
