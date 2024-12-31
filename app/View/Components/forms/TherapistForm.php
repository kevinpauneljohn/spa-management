<?php

namespace App\View\Components\forms;

use Illuminate\View\Component;

class TherapistForm extends Component
{
    public $spaId;
    public $therapist;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spaId = null, $therapist)
    {
        $this->spaId = $spaId;
        $this->therapist = $therapist;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.therapist-form');
    }
}
