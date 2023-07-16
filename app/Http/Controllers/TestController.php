<?php

namespace App\Http\Controllers;

use App\Models\Spa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class TestController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {

//        return Carbon::parse(\date('2023-07-11 24:00:00'))->setTimezone('Asia/Manila')->format('Y-m-d h:i:s a');
//        return Carbon::parse(\date('2023-07-11 09:00:00'))->setTimezone('Asia/Manila')->format('Y-m-d h:i:s a');

        return view('Payroll.index');
//        $spa = Spa::where('name','Thai Khun Lounge & Spa')->first();
//        return User::where('spa_id',$spa->id)->whereHas("roles", function($q){ $q->where("name", "front desk"); })->first();
//        $spa = Spa::where('name','Thai Khun Lounge & Spa')->first();
//        $therapist_one = collect($spa->therapists)->pluck('id')->random();
//        return $spa->therapists->first()->transactions()->where('therapist_2','=',null)->get();

    }
}
