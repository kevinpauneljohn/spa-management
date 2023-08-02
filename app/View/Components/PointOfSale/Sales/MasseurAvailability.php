<?php

namespace App\View\Components\PointOfSale\Sales;

use App\Models\Spa;
use App\Services\PointOfSales\MasseurAvailabilityService;
use Illuminate\View\Component;

class MasseurAvailability extends Component
{
    public $spaId;
    public $spa;
    public $availableTherapists;
    public $excluded;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spaId, MasseurAvailabilityService $availabilityService)
    {
        $this->spa = Spa::findOrFail($spaId);
        $this->availableTherapists = $availabilityService->masseurs($spaId);
        $this->excluded = $this->availableTherapists->pluck('id');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.point-of-sale.sales.masseur-availability');
    }
}
