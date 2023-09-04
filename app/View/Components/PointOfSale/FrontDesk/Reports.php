<?php

namespace App\View\Components\PointOfSale\FrontDesk;

use App\Models\SalesShift;
use Illuminate\View\Component;

class Reports extends Component
{
    public $spaId;
    public $salesShifts;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spaId)
    {
        $this->spaId = $spaId;
        $this->salesShifts = SalesShift::where('user_id',auth()->user()->id)
            ->orderBy('id','desc')->limit(6)->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.point-of-sale.front-desk.reports');
    }
}
