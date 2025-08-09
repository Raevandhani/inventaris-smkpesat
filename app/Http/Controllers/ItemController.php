<?php

namespace App\Http\Controllers;

use App\Models\Items;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Items::all();
        $breadcrumbs = [
            ['label' => 'List Barang']
        ];
        return view('dashboard.items.index', compact('items', 'breadcrumbs'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['label' => 'List Barang', 'url' => route('items.index')],
            ['label' => 'Create']
        ];
        return view('dashboard.items.create', compact('breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
           "name" => "required",
            "category" => "required",
            "condition" => "nullable",
            "total_stock" => "required",
            "status" => "nullable",
        ]);

        $data = $request->all();
        Items::create($data);
        
        return redirect('items');
    }

    public function edit(string $id)
    {
        $items = Items::findorFail($id);
        $breadcrumbs = [
            ['label' => 'List Barang', 'url' => route('items.index')],
            ['label' => 'Edit']
        ];
        return view('dashboard.items.edit', compact('items','breadcrumbs'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
           "name" => "required",
            "category" => "required",
            "condition" => "nullable",
            "total_stock" => "required",
            "status" => "nullable",
        ]);

        $items = Items::findorFail($id);

        $data = $request->all();
        $items->update($data);
        
        return redirect('items');
    }

    public function destroy(string $id)
    {
        $items = Items::findorFail($id);
        $items->delete();

        return redirect('items');
    }
}
