<?php

namespace App\View\Components\Clients;

use App\Models\Spa;
use Illuminate\View\Component;

class BookClientModal extends Component
{
    public $spa;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spaId)
    {
        $this->spa = Spa::findOrFail($spaId);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.clients.book-client-modal');
    }
}
