<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Items;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('items')
        ->withSum('items', 'total_stock')
        ->latest()
        ->get();

        $editCategory = null;
        if ($request->has('edit')) {
            $editCategory = Category::find($request->query('edit'));
        }

        $breadcrumbs = [
            ['label' => 'Categories']
        ];

        return view('dashboard.administration.categories.index', compact('categories','breadcrumbs','editCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create(['name' => $request->name]);

        return redirect()->route('categories.index');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name]);

        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(string $id)
    {
        $categories = Category::findorFail($id);
        $categories->delete();

        return redirect('categories');
    }
}
