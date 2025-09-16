<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $borrowedItems = Borrow::with(['item', 'location', 'user'])
            ->where('status', 'ongoing')
            ->get();

        return view('dashboard', compact('borrowedItems'));
    }
}
