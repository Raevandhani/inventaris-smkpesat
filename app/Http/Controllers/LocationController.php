<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        // hasRole Is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }
        
        $locations = Location::withCount('borrow')->get();
        $edit = null;
        if ($request->has('edit')) {
            $edit = Location::find($request->query('edit'));
        }

        $breadcrumbs = [
            ['label' => 'Location']
        ];

        return view('dashboard.administration.locations.index', compact('locations','breadcrumbs','edit'));
    }

    public function store(Request $request)
    {
        // hasRole Is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:locations,name',
        ]);

        Location::create(['name' => $request->name]);

        return redirect()->route('locations.index');
    }

    public function update(Request $request, string $id)
    {
        // hasRole Is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:locations,name,' . $id,
        ]);

        $category = Location::findOrFail($id);
        $category->update(['name' => $request->name]);

        return redirect()->route('locations.index')->with('success', 'Location updated.');
    }

    public function destroy(string $id)
    {
        // hasRole Is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }
        
        $locations = Location::findorFail($id);
        $locations->delete();

        return redirect('locations');
    }
}
