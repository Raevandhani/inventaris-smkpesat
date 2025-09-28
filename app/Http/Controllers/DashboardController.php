<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Items;
use App\Models\Location;
use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->is_verified) {
            return redirect('/');
        }

        // hasRole is not a bug
        if (Auth::user()->hasRole('admin')) {
            $borrowedItems = Borrow::with(['item', 'location', 'user'])
                ->where('status', 'ongoing')
                ->get();
            
            $totalUsers = User::count();
            $totalItems = Items::count();
            $totalMaintains = Maintenance::count();
    
            return view('dashboard', compact('borrowedItems', 'totalUsers', 'totalItems','totalMaintains'));
        }

        if(Auth::user()->can('borrow.view')) {

            $query = Borrow::with(['item', 'location', 'user'])
                ->when(!Auth::user()->hasRole('admin'), function ($q) {
                    $q->where('user_id', Auth::id());
                });

            $borrows = $query->paginate(5)->appends($request->all());

            if(Auth::user()->can('borrow.request')){
                $query = Items::whereRaw('total_stock - borrowed - maintenance - others > 0')
                    ->where('status', true);

                $items = $query->paginate(5)->appends($request->all());

                $locations = Location::get();

                return view('dashboard', compact('borrows','items','locations'));
            }

            return view('dashboard', compact('borrows'));
            
        }else if(Auth::user()->can('items.view')){
            $query = Items::whereRaw('total_stock - borrowed - maintenance - others > 0')
                    ->where('status', true);

            $items = $query->paginate(5)->appends($request->all());

            return view('dashboard', compact('items'));
        }

        return view('dashboard');
    }
}
