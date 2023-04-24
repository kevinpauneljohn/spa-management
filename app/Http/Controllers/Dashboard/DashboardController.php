<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('super admin')) {
            return view('Dashboard.dashboard');
        } else if (auth()->user()->hasRole('owner')) {
            return view('Owner.index');
        } else if (auth()->user()->hasRole('receptionist')) {
            return redirect('/receptionist-dashboard');
        }
    }
}
