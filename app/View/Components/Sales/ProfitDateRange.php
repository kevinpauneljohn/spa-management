<?php

namespace App\View\Components\Sales;

use Illuminate\View\Component;

class ProfitDateRange extends Component
{
    public $spaId;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spaId)
    {
        $this->spaId = $spaId;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.sales.profit-date-range');
    }
}
