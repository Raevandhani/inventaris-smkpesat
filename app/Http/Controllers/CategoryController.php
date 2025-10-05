<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // hasRole is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }
        
        $query = Category::withCount('items')
                    ->withSum('items', 'total_stock');

        $editCategory = null;
        if ($request->has('edit')) {
            $editCategory = Category::find($request->query('edit'));
        }

        $n = 6;

        $categories = $query->paginate($n)->appends($request->all());

        $breadcrumbs = [
            ['label' => 'Categories']
        ];

        return view('dashboard.administration.categories.index', compact('categories','breadcrumbs','editCategory','n'));
    }

    public function store(Request $request)
    {
        // hasRole is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create(['name' => $request->name]);

        return redirect()->route('categories.index');
    }

    public function update(Request $request, string $id)
    {
        // hasRole is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name]);

        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(string $id)
    {
        // hasRole is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $categories = Category::findorFail($id);
        $categories->delete();

        return redirect('categories');
    }
}
