<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        // hasRole Is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }
        
        $roles = Role::with('users')->get();

        $editRole = null;

        if ($request->has('edit')) {
            $editRole = Role::find($request->query('edit'));
        }

        $breadcrumbs = [
            ['label' => 'Roles']
        ];
        return view('dashboard.administration.roles.index', compact('roles','editRole','breadcrumbs'));
    }

    public function store(Request $request)
    {
        // hasRole Is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create([
            'name' => strtolower($request->name),
            'guard_name' => 'web',
        ]);

        return redirect()->route('roles.index');
    }

    public function update(Request $request, string $id)
    {
        // hasRole Is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);
    
        $role = Role::findOrFail($id);
    
        $role->update([
            'name' => strtolower($request->name),
        ]);
    
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    
        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(string $id)
    {
        // hasRole Is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }
        
        $roles = Role::findorFail($id);
        $roles->delete();

        return redirect('roles');
    }
}
