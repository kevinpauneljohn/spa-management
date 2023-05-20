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
        } else if (!empty($user)) {
            return redirect('/receptionist-dashboard');
        }
    }
}
