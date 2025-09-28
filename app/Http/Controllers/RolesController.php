<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
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
        $permissions = Permission::all();

        $editRole = null;
        if ($request->has('edit')) {
            $editRole = Role::find($request->query('edit'));
        }

        $breadcrumbs = [
            ['label' => 'Roles']
        ];
        return view('dashboard.administration.roles.index', compact('roles','editRole','breadcrumbs','permissions'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => strtolower($request->name),
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function update(Request $request, string $id)
    {
        if (!Auth::user()->hasRole('admin')) {
        return redirect()->route('dashboard');
        }
    
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);
    
        $role = Role::findOrFail($id);
    
        $role->update([
            'name' => strtolower($request->name),
        ]);
    
        $role->syncPermissions($request->permissions ?? []);
    
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    
        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(string $id)
    {
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('dashboard');
        }

        $role = Role::findOrFail($id);

        $role->syncPermissions([]);

        $role->users()->detach();

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
