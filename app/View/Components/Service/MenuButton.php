<?php

namespace App\View\Components\Service;

use Illuminate\View\Component;

class MenuButton extends Component
{
    public $title;
    public $spa;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $spa)
    {
        $this->title = $title;
        $this->spa = $spa;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.service.menu-button');
    }
}
