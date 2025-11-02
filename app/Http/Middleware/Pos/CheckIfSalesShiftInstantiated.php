<?php

namespace App\Http\Middleware\Pos;

use App\Models\SalesShift;
use Closure;
use Illuminate\Http\Request;

class CheckIfSalesShiftInstantiated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if($user->hasRole(['front desk','manager']))
        {
            $salesShift = SalesShift::where('spa_id',$user->spa_id)
                ->where('start_shift','!=',null)
                ->where('end_shift','=',null);
            if($salesShift->count() === 0)
            {
                return redirect(route('required.start-shift',['spaId' => $user->spa_id]));
            }elseif ($salesShift->where('user_id','!=',auth()->user()->id)->where('completed',false)->count() > 0){
                return redirect(route('access-denied'));
            }
        }
        return $next($request);
    }
}
