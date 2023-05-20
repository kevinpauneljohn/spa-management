<?php

namespace App\View\Components\Inventory;

use Illuminate\View\Component;

class UpdateQuantity extends Component
{
    public $inventory;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($inventory)
    {
        $this->inventory = $inventory;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.inventory.update-quantity');
    }
}
