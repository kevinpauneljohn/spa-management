<?php

namespace App\View\Components\Service;

use App\Models\ServiceCategory;
use Illuminate\View\Component;

class Service extends Component
{
    public $spa;
    public $range;
    public $categories;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spa)
    {
        $this->spa = $spa;
        $this->range = $this->minutes();
        $this->categories = ServiceCategory::where('spa_id', $spa->id)->get();
    }

    private function minutes()
    {
        $range = range(5, 300, 5);
        $data = [];
        foreach ($range as $ranges) {
            $data [$ranges] = $ranges;
        }
        return $data;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.service.service');
    }
}
