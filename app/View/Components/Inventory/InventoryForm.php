<?php

namespace App\View\Components\Inventory;

use App\Services\InventoryService;
use App\Services\UserService;
use Illuminate\View\Component;

class InventoryForm extends Component
{
    public $formDefault;
    public $spas;
    public $categories;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($formDefault = true, UserService $userService, InventoryService $inventoryService)
    {
        $this->formDefault = $formDefault;
        $this->spas = $userService->get_staff_owner()->spas;
        $this->categories = $inventoryService->categories($userService);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.inventory.inventory-form');
    }
}
