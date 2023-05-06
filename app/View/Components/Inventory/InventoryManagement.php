<?php

namespace App\View\Components\Inventory;

use Illuminate\View\Component;

class InventoryManagement extends Component
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
        return view('components.inventory.inventory-management');
    }
}
