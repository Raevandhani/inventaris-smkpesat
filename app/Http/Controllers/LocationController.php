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
        
        $query = Location::withCount('borrow');
        
        $edit = null;
        if ($request->has('edit')) {
            $edit = Location::find($request->query('edit'));
        }

        $n = 6;

        $locations = $query->paginate($n)->appends($request->all());

        $breadcrumbs = [
            ['label' => 'Location']
        ];

        return view('dashboard.administration.locations.index', compact('locations','breadcrumbs','edit','n'));
    }

    public function store(Request $request)
    {
        // hasRole Is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:locations,name',
        ],[
            'name.unique' => 'This location has already exist.'
        ]);

        Location::create(['name' => $request->name]);

        return redirect()->route('locations.index')->with('success', 'Location created successfully: "'.$request->name.'"');
    }

    public function update(Request $request, string $id)
    {
        // hasRole Is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $locations = Location::findOrFail($id);
        $oldName = $locations->name;

        $request->validate([
            'name' => 'required|string|max:255|unique:locations,name,' . $id,
        ]);

        $locations->update(['name' => $request->name]);

        return redirect()->route('locations.index')->with('success', "Locations name updated successfully: \"{$oldName}\" → \"{$request->name}\"");
    }

    public function destroy(string $id)
    {
        // hasRole Is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }
        
        $locations = Location::findorFail($id);
        $locations->delete();

        return redirect('locations')->with('deleted', 'Location deleted successfully: "'.$locations->name.'"');
    }

    // Unused
    public function create(){
        return redirect()->route('locations.index');
    }
    public function show(){
        return redirect()->route('locations.index');
    }
    public function edit(){
        return redirect()->route('locations.index');
    }
}
