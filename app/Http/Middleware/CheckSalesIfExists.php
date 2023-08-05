<?php

namespace App\Http\Middleware;

use App\Models\Sale;
use Closure;
use Illuminate\Http\Request;

class CheckSalesIfExists
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
        $spaId = $request->segment(3);
        $salesId = $request->segment(4);
        if(Sale::where('id',$salesId)->where('spa_id',$spaId)->count() === 0)
        {
            abort(404);
        }
        return $next($request);
    }
}
