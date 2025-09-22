<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Items;
use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $borrowedItems = Borrow::with(['item', 'location', 'user'])
            ->where('status', 'ongoing')
            ->get();
        
        $totalUsers = User::count();
        $totalItems = Items::count();
        $totalMaintains = Maintenance::count();

        return view('dashboard', compact('borrowedItems', 'totalUsers', 'totalItems','totalMaintains'));
    }
}
