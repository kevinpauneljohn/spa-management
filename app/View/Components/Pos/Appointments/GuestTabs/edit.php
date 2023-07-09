<?php

namespace App\View\Components\Pos\Appointments\GuestTabs;

use Illuminate\View\Component;

class edit extends Component
{
    public $id;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.pos.appointments.guest-tabs.edit');
    }
}
