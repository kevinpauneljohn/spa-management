<?php

namespace App\Http\Middleware;

use App\Models\Sale;
use Closure;
use Illuminate\Http\Request;

class CheckSalesIfExistsForPayment
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
        ///this will allow user to process payment if front desk/manager user spa_id matches the spa_id of sales
        if($user->hasAnyRole('front desk','manager'))
        {
            if(Sale::where('id',$request->segment(2))->where('spa_id',$user->spa_id)->count() === 0)
            {
               abort(403);
            }
        }
        return $next($request);
    }
}
