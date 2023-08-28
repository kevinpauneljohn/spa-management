<?php

namespace App\Services\PointOfSales\Shift;

use App\Models\SalesShift;

class ShiftService
{

    public function start($money)
    {
        return SalesShift::create([
            'start_shift' => now(),
            'user_id' => auth()->user()->id,
            'spa_id' => auth()->user()->spa_id,
            'start_money' => $money,
            'completed' => false
        ]);
    }

    public function end($spaId)
    {
        $user = auth()->user();
        $salesShift = SalesShift::where('spa_id',$spaId)
            ->where('user_id',$user->id)->where('end_shift','=',null)->where('completed','=',false)->first();
        $salesShift->end_shift = now();
        $salesShift->completed = true;
        return (bool)$salesShift->save();
    }

    public function abortDirectAccessToStartShiftPageIfExists()
    {
        $user = auth()->user();
        $salesShift = SalesShift::where('spa_id',$user->spa_id)
            ->where('user_id',$user->id);
        if($salesShift->where('start_shift','!=',null)
                ->where('end_shift','=',null)
                ->count() > 0)
        {
            abort(404);
        }else if($salesShift->where('completed',false)){
            abort(404);
        }
    }
}
