<?php

namespace App\Http\Controllers;

use App\Models\Therapist;
use App\Models\Transaction;
use App\Services\PayrollService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;
use stdClass;

class PayrollController extends Controller
{
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        return view('Payroll.index');
    }


    public function showDate(Request $request){

        [$start, $end] = array_map(function($date) {
            return Carbon::parse($date)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        }, explode('-', $request->daterange));
    
        $data = Therapist::with(['transactions' => function ($query) use ($start, $end) {
            $query->whereBetween('created_at', [$start, $end]);
        }])->get()
        ->map(function ($therapist) {
            $amount = $therapist->transactions->sum('amount');
            return (object) [
                'id' => $therapist->id,
                'fullname' => implode(' ', [$therapist->firstname, $therapist->lastname]),
                'amount' => $amount,
                'TotalCommission' => ((($therapist->commission_percentage / 100) * $amount) + $therapist->commission_flat),
            ];
        })->all();

        return $data;

    }

    
    public function getSummary(Request $request, $id) {
        
        $start = Carbon::parse($request->datestart)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $end = Carbon::parse($request->dateEnd)->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');

    
        $therapist = Therapist::select('firstname', 'lastname', 'id')
        ->where('id', $id)->first();

        $transaction = Transaction::select('service_name', 'amount', 'client_id', 'created_at')
        ->whereDate('transactions.created_at', '>=',$start)
        ->whereDate('transactions.created_at', '<=', $end)
        ->where('therapist_1', $id)->get();


       $collect = array(
        "therapist" => $therapist,
        "transaction" => $transaction
        );

        return $collect;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    
}
