<?php

namespace App\View\Components\PointOfSale\Transactions;

use App\Models\Service;
use App\Models\Spa;
use App\Services\UserService;
use Illuminate\View\Component;

class SalesTransactionClientsTable extends Component
{
    public $spaId;
    public $saleId;
    public $displayAllColumns;

    public $tableId;

    public $tableClass;

    public $clients;
    public $services;
    public $therapists;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct( UserService $userService,$spaId, $saleId, $tableId, $tableClass = 'display-sales-client', $displayAllColumns = true)
    {
        $this->spaId = $spaId;
        $this->saleId = $saleId;
        $this->displayAllColumns = $displayAllColumns;
        $this->tableId = $tableId;
        $this->tableClass = $tableClass;
        $this->clients = $userService->get_staff_owner()->clients;
        $this->services = Service::where('spa_id',$spaId)->get();
        $this->therapists = Spa::find($spaId)->therapists;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.point-of-sale.transactions.sales-transaction-clients-table');
    }
}
