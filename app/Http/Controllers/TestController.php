<?php

namespace App\Http\Controllers;

use App\Models\Spa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;

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

//        $test = Permission::create(['name' => 'testing only']);
//        $user = auth()->user()->givePermissionTo($test);
//        return auth()->user()->hasPermissionTo('testing only');
        return auth()->user()->permissions;
//        return view('Payroll.index');
//        $spa = Spa::where('name','Thai Khun Lounge & Spa')->first();
//        return User::where('spa_id',$spa->id)->whereHas("roles", function($q){ $q->where("name", "front desk"); })->first();
//        $spa = Spa::where('name','Thai Khun Lounge & Spa')->first();
//        $therapist_one = collect($spa->therapists)->pluck('id')->random();
//        return $spa->therapists->first()->transactions()->where('therapist_2','=',null)->get();

//        return Activity::whereIn('description',[
//            'created transaction','voided a transaction'
//        ])->where('spa_id','97d9f261-6d8a-4954-94ff-dad7a6b94b57')->paginate();

    }
}
