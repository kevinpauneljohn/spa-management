<?php

namespace App\View\Components\Pos\Appointments\GuestTabs;

use Illuminate\View\Component;

class stop_time extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.pos.appointments.guest-tabs.stop_time');
    }
}
