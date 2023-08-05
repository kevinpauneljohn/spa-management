<?php

namespace App\Http\Middleware\Pos;

use App\Models\Transaction;
use Closure;
use Illuminate\Http\Request;

class SalesTransactionVerifier
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
        $transaction = Transaction::where('id',$request->segment(3));
        if($user->hasAnyRole('front desk','manager'))
        {
            if($transaction->where('spa_id',$user->spa_id)->count() === 0
                || $transaction->where('spa_id',$request->segment(2))->count() === 0)
            {
                abort(404);
            }

        }
        elseif ($user->hasAnyRole('owner'))
        {
            $spaIds = collect($user->owner->spas)->pluck('id')->toArray();
            if($transaction->whereIn('spa_id',$spaIds)->count() === 0)
            {
                abort(404);
            }
        }
        return $next($request);
    }
}
