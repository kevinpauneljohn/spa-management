<?php

namespace App\Http\Middleware;

use App\Models\Spa;
use App\Services\UserService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlyEmployeeOrOwnerOfTheSpaAndCheckSalesInstance
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

        $spaOwner = Spa::findOrFail($request->segment(3))->owner;
        $user = Auth::user();
        if($user->hasRole('owner'))
        {
            if($user->owner->id !== $spaOwner->id)
            {
                abort(404);
            }

        }
        elseif ($user->hasRole(['therapist','manager','front desk']))
        {
            if($user->spa->owner->id !== $spaOwner->id)
            {
                abort(404);
            }
        }
        return $next($request);
    }
}
