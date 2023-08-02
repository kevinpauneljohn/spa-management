<?php

namespace App\View\Components\Inventory;

use App\Models\Spa;
use App\Models\UnitOfMeasurement;
use App\Services\InventoryService;
use App\Services\UserService;
use Illuminate\View\Component;

class InventoryForm extends Component
{
    public $spaId;
    public $formDefault;
    public $spas;
    public $categories;
    public $measurements;

    /**
     * Create a new component instance.
     *
     * @param bool $formDefault
     * @param UserService $userService
     * @param InventoryService $inventoryService
     * @param null $spaId
     */
    public function __construct(bool $formDefault = true, UserService $userService, InventoryService $inventoryService, $spaId = null)
    {
        $this->spaId = $spaId;
        $this->formDefault = $formDefault;
        $this->spas = $spaId !== null ? Spa::find($spaId) : $userService->get_staff_owner()->spas;
        $this->categories = $inventoryService->categories($userService);
        $this->measurements = UnitOfMeasurement::all();
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
