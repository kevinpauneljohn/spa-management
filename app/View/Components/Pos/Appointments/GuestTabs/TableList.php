<?php

namespace App\View\Components\Pos\Appointments\GuestTabs;

use Illuminate\View\Component;

class TableList extends Component
{
    public $spaId;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spaId = null)
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
        return view('components.pos.appointments.guest-tabs.table-list');
    }
}
