<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::where('id', auth()->user()->id)->permission('access pos')->first();

        if (auth()->user()->hasRole('super admin')) {
            return view('Dashboard.dashboard');
        } else if (auth()->user()->hasRole('owner')) {
            return redirect('owner-dashboard');
        } else if (!empty($user) && auth()->user()->hasRole('front desk')) {
            return redirect(route('point-of-sale.show',['point_of_sale' => auth()->user()->spa_id]));
//            return redirect('/receptionist-dashboard');
        } else {
            return view('Dashboard.dashboard');
        }
    }
}
