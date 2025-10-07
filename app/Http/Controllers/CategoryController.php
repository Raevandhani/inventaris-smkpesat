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
        ], [
            'name.unique' => 'The category name has already exists.',
        ]);

        Category::create(['name' => $request->name]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully: "'.$request->name.'"');
    }

    public function update(Request $request, $id)
    {
        // hasRole is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $category = Category::findOrFail($id);
        $oldName = $category->name;

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ], [
            'name.unique' => 'The category name has already exists.',
        ]);

        $category->update(['name' => $request->name]);

        return redirect()
            ->route('categories.index')
            ->with('success', "Category name updated successfully: \"{$oldName}\" → \"{$request->name}\"");
    }

    public function destroy(string $id)
    {
        // hasRole is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $categories = Category::findorFail($id);
        $categories->delete();

        return redirect('categories')->with('deleted','Category deleted successfully: "'.$categories->name.'"');
    }

    // Unused
    public function create(){
        return redirect()->route('categories.index');
    }
    public function show(){
        return redirect()->route('categories.index');
    }
    public function edit(){
        return redirect()->route('categories.index');
    }
}
