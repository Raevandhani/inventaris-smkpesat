<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Items;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ParagonIE\Sodium\Core\Curve25519\Ge\P2;

class BorrowController extends Controller
{
    public function index()
    {
        $borrows = Borrow::get();

        $breadcrumbs = [
            ['label' => 'Borrow']
        ];
        return view('dashboard.borrow.index', compact('borrows', 'breadcrumbs'));
    }

    public function items()
    {
        $items = Borrow::get();
        $breadcrumbs = [
            ['label' => 'Borrow']
        ];
        return view('dashboard.borrow.index', compact('items', 'breadcrumbs'));
    }

    public function create()
    {
        $items = Items::where('status', 'available')->where('available', '>', 0)->get();
        $locations = Location::get();

        $breadcrumbs = [
            ['label' => 'Borrows', 'url' => route('borrows.index')],
            ['label' => 'Create']
        ];
        return view('dashboard.borrow.create', compact('items','locations','breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "user_id"     => "nullable",
            "item_id"     => "required|exists:items,id",
            "location_id" => "required",
            "quantity"    => "required|integer|min:1",
            "borrow_date" => "required|date",
            "return_date" => "nullable|date",
            "status"      => "nullable",
        ]);

        $item = Items::findOrFail($request->item_id);

        if ($request->quantity > $item->available) {
            return back()
                ->withErrors([
                    'quantity' => "Only {$item->available} item(s) are available right now.",
                ])
                ->withInput();
        }

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        Borrow::create($data);

        return redirect('borrows');
    }


    public function edit(string $id)
    {
        
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            "item_id" => "required",
            "location_id" => "required",
            "quantity" => "required",
            "borrow_date" => "required",
            "return_date" => "nullable",
            "status" => "nullable",
        ]);

        $borrows = Borrow::findorFail($id);

        $data = $request->all();
        $borrows->update($data);
        
        return redirect('borrows');
    }

    public function finished(Request $request, string $id){

    }

    public function accepted(Request $request, string $id)
    {
        $borrow = Borrow::findOrFail($id);

        $item = Items::findOrFail($borrow->item_id);

        $borrow->update([
            'status' => 'ongoing',
        ]);

        $item->available   = $item->available - $borrow->quantity;
        $item->unavailable = ($item->unavailable ?? 0) + $borrow->quantity;
        $item->save();

        if ($item->available < 0) {
            $item->available = 0;
        }

        $item->save();

        return redirect('borrows');
    }


    // public function declined(Request $request, string $id){
    //     $request->validate([
    //         "status" => "required",
    //     ]);

    //     $borrows = Borrow::findorFail($id);

    //     $data = $request->all();
    //     $borrows->update($data);
        
    //     return redirect('borrows');
    // }

    public function destroy(string $id)
    {
        $borrows = Borrow::findorFail($id);
        $borrows->delete();

        return redirect('borrows');
    }
}
