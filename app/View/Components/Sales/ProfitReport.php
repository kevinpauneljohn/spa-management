<?php

namespace App\View\Components\Sales;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ProfitReport extends Component
{
    public $sales;
    public $expenses;
    public $profit;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spaId)
    {
        $current_year = now()->format('Y');
        $this->sales = DB::table('sales')->where('spa_id',$spaId)
            ->where('payment_status','completed')
            ->whereYear('created_at','=',$current_year)->sum('amount_paid');

        $this->expenses = DB::table('expenses')->whereYear('date_expended','=',$current_year)->where('spa_id',$spaId)->sum('amount');

        $this->profit = $this->sales - $this->expenses;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.sales.profit-report');
    }
}
