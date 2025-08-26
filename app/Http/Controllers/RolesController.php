<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesController extends Controller
{
    public function index(Request $request)
    {
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
        $roles = Role::findorFail($id);
        $roles->delete();

        return redirect('roles');
    }
}
