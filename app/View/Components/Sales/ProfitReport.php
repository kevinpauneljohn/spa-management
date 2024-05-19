<?php

namespace App\View\Components\Sales;

use App\Models\Sale;
use App\Models\Spa;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ProfitReport extends Component
{
    public $total_sales;
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
        $sales = Spa::find($spaId)->sales()->whereYear('created_at',$current_year)
            ->where('payment_status','completed')->get();

        $total_transactions = $sales->pluck('transactions')->flatten()->sum('amount');
        $total_vouchers = $sales->pluck('discounts')->flatten()->sum('price');

        $this->total_sales = $total_transactions + $total_vouchers;

        $this->expenses = DB::table('expenses')->whereYear('date_expended','=',$current_year)->where('spa_id',$spaId)->sum('amount');

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
