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

        $items = Items::whereRaw('total_stock - borrowed - maintenance - others > 0')
                    ->where('status', true)
                    ->paginate(5)
                    ->appends($request->all());

        $locations = Location::get();

        $borrows = Borrow::with(['item', 'location', 'user'])
                ->when(!Auth::user()->hasRole('admin'), function ($q) {
                    $q->where('user_id', Auth::id());
                })
                ->paginate(5)->appends($request->all());

        $borrowedItems = Borrow::with(['item', 'location', 'user'])
                ->where('status', 'ongoing')
                ->get();
            
        $totalUsers = User::count();
        $totalItems = Items::count();
        $totalMaintains = Maintenance::count();

        $data = match (true) {
            Auth::user()->hasRole('admin') => compact('borrowedItems', 'totalUsers', 'totalItems', 'totalMaintains'),
            Auth::user()->can('borrow.view') && Auth::user()->can('borrow.request') => compact('borrows','items','locations'),
            Auth::user()->can('borrow.view') => compact('borrows'),
            Auth::user()->can('items.view') => compact('items'),
            default => [],
        };

        $data = array_merge([
            'borrows' => $borrows ?? collect(),
            'items' => $items ?? collect(),
            'locations' => $locations ?? collect(),
            'borrowedItems' => $borrowedItems ?? collect(),
            'totalUsers' => $totalUsers ?? 0,
            'totalItems' => $totalItems ?? 0,
            'totalMaintains' => $totalMaintains ?? 0,
            'hasPermission' => !empty($data),
        ], $data);

        return view('dashboard', $data);
    }

    // Unused
    public function create(){
        return redirect()->route('/');
    }
    public function store(){
        return redirect()->route('/');
    }
    public function show(){
        return redirect()->route('/');
    }
    public function edit(){
        return redirect()->route('/');
    }
    public function update(){
        return redirect()->route('/');
    }
    public function destroy(){
        return redirect()->route('/');
    }
}
