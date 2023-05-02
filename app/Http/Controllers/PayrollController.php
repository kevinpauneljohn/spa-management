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
      
        $date =  explode("- ",$request->daterange);
        $start = Carbon::parse($date[0])->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $end = Carbon::parse($date[1])->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');
        $data = DB::table('therapists')
        ->join('transactions', 'therapists.id', '=', 'transactions.therapist_1')
        ->whereDate('transactions.created_at', '>=',$start)
        ->whereDate('transactions.created_at', '<=', $end)
        ->select('therapists.id', 'therapists.firstname', 'therapists.lastname', 'therapists.commission_percentage', 'transactions.amount', 
        'therapists.commission_flat','transactions.service_name','transactions.plus_time')
        ->get()
        ->groupBy('id')
        ->map(function ($group) {
            $fullname = implode(' ', [$group[0]->firstname, $group[0]->lastname]);
            return (object) [
                'id' => $group[0]->id,
                'fullname' => $fullname,
                'amount' => $group->sum('amount'),
                'TotalCommission' => ($group[0]->commission_percentage / 100) * $group->sum('amount') + $group[0]->commission_flat,
                'service_name' => $group[0]->service_name,
                'plus_time' => $group[0]->plus_time
            ];
        })
        ->values()
        ->all();
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
