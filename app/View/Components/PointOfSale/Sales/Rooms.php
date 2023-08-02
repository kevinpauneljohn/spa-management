<?php

namespace App\View\Components\PointOfSale\Sales;

use App\Models\Spa;
use Carbon\Carbon;
use Illuminate\View\Component;

class Rooms extends Component
{
    public $spaId;
    public $spa;
    public $rooms;
    public $threshold;
    public $row;
    public $takenRoom;
    public $transactions;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spaId)
    {
        $this->spa = Spa::find($spaId);
        $this->rooms = $this->spa->number_of_rooms;
        $this->transactions = collect($this->spa->sales()->with('transactions',function ($transaction){
            $transaction->where('end_time','>',now());
        })
            ->where('payment_status','pending')->orWhere('payment_status','completed')->get())->pluck('transactions')->flatten();
        $this->takenRoom = $this->transactions->pluck('room_id');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.point-of-sale.sales.rooms');
    }
}
