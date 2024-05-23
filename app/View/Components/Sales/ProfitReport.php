<?php

namespace App\View\Components\Sales;

use App\Models\Expense;
use App\Models\Sale;
use App\Models\Spa;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ProfitReport extends Component
{
    public $total_sales;
    public $expenses;
    public $profit;
    public $spaId;
    public $current_month;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($spaId)
    {
        $this->spaId = $spaId;
        $this->current_month = now()->format('m');
        $sales = Spa::find($spaId)->sales()->whereMonth('created_at',$this->current_month)
            ->where('payment_status','completed')->get();

        $total_transactions = $sales->pluck('transactions')->flatten()->sum('amount');
        $total_vouchers = $sales->pluck('discounts')->flatten()->sum('price');

        $this->total_sales = $total_transactions + $total_vouchers;

//        $this->expenses = DB::table('expenses')->whereMonth('date_expended','=',$this->current_month)->where('spa_id',$spaId)->sum('amount');
        $this->expenses = Expense::where('spa_id','=','774a6ccf-d0e6-4cb7-a56c-9f0f470d3272')->whereMonth('date_expended','=',$this->current_month)->sum('amount');

        $this->profit = $this->total_sales - $this->expenses;
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
