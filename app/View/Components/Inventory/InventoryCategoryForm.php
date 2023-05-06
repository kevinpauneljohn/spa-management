<?php

namespace App\View\Components\Inventory;

use Illuminate\View\Component;

class InventoryCategoryForm extends Component
{
    public $formDefault;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($formDefault = true)
    {
        $this->formDefault = $formDefault;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.inventory.inventory-category-form');
    }
}
