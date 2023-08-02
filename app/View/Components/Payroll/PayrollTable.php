<?php

namespace App\View\Components\Payroll;

use App\Models\Spa;
use Illuminate\View\Component;

class PayrollTable extends Component
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
        return view('components.payroll.payroll-table');
    }
}
