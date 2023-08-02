<?php

namespace App\View\Components\PointOfSale\Logs;

use Illuminate\View\Component;
use Spatie\Activitylog\Models\Activity;

class TransactionLog extends Component
{
    public $transactionLogs;
    public $spaId;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spaId)
    {
        $this->spaId = $spaId;
        $this->transactionLogs = $this->activityLogs($spaId);

    }

    /**
     * @param $spaId
     * @return mixed
     */
    private function activityLogs($spaId)
    {
        return Activity::whereIn('description',[
            'created transaction','voided a transaction','Client Isolated','Sales Payment'
        ])->where('spa_id',$spaId)->orderBy('created_at','desc')->simplePaginate(15);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.point-of-sale.logs.transaction-log');
    }
}
