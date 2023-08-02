<?php

namespace App\View\Components\PointOfSale\Transactions;

use Illuminate\View\Component;

class SalesTransactionClientsTable extends Component
{
    public $spaId;
    public $saleId;
    public $displayAllColumns;

    public $tableId;

    public $tableClass;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spaId, $saleId, $tableId, $tableClass = 'display-sales-client', $displayAllColumns = true)
    {
        $this->spaId = $spaId;
        $this->saleId = $saleId;
        $this->displayAllColumns = $displayAllColumns;
        $this->tableId = $tableId;
        $this->tableClass = $tableClass;
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
