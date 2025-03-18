<?php

namespace App\View\Components\Hr;

use Illuminate\View\Component;

class Benefits extends Component
{
    public $employee;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($employee)
    {
        $this->employee = $employee;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.hr.benefits');
    }
}
