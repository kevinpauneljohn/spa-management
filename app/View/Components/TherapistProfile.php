<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TherapistProfile extends Component
{
    public $therapist;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($therapist)
    {
        $this->therapist = $therapist;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.therapist-profile');
    }
}
