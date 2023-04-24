<?php

namespace App\Http\Middleware;

use App\Models\Spa;
use Closure;
use Illuminate\Http\Request;

class CheckIfSpaBelongsToOwner
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
        if(Spa::where('owner_id',auth()->user()->owner->id)->count() < 1)
            abort(403,'Unauthorized access');
        return $next($request);
    }
}
