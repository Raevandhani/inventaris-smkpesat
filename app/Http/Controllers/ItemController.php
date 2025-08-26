<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Items;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Items::with('category')->get();
        $breadcrumbs = [
            ['label' => 'Items']
        ];
        return view('dashboard.items.index', compact('items', 'breadcrumbs'));
    }

    public function create()
    {
        $categories = Category::all();
        $breadcrumbs = [
            ['label' => 'Items', 'url' => route('items.index')],
            ['label' => 'Create']
        ];
        return view('dashboard.items.create', compact('categories','breadcrumbs'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        
        $request->validate([
            "name" => "required",
            "category_id" => "required",
            "condition" => "nullable",
            "available" => "nullable",
            "total_stock" => "required",
            "status" => "nullable",
        ]);

        $data['available'] = $data['total_stock'];
        Items::create($data);
        
        return redirect('items');
    }

    public function edit(string $id)
    {
        $items = Items::findorFail($id);
        $categories = Category::all();
        $breadcrumbs = [
            ['label' => 'Items', 'url' => route('items.index')],
            ['label' => 'Edit']
        ];
        return view('dashboard.items.edit', compact('items','categories','breadcrumbs'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => "required",
            "category_id" => "required",
            "condition" => "nullable",
            "total_stock" => "required|integer|min:0",
            "status" => "nullable",
        ]);

        $items = Items::findOrFail($id);

        $newTotal = (int) $request->input('total_stock');
        $currentTotal = $items->total_stock;
        $currentAvailable = $items->available;
        $currentUnavailable = $currentTotal - $currentAvailable;

        if ($newTotal > $currentTotal) {
            $addedStock = $newTotal - $currentTotal;
            $items->available = $currentAvailable + $addedStock;
        }

        elseif ($newTotal < $currentTotal) {
            if ($newTotal < $currentUnavailable) {
                return back()->withErrors([
                    'total_stock' => "Total stock cannot be less than ($currentUnavailable) of unavailable items"
                ])->withInput();
            }

            $items->available = $newTotal - $currentUnavailable;
        }

        $items->fill($request->except('total_stock'));
        $items->total_stock = $newTotal;
        $items->save();

        return redirect('items')->with('success', 'Item updated successfully.');
    }


    public function destroy(string $id)
    {
        $items = Items::findorFail($id);
        $items->delete();

        return redirect('items');
    }
}
