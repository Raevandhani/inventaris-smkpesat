<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        // hasRole Is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }
        
        $users = User::latest()->get();
        $roles = Role::get();

        $edit = null;
        if ($request->has('edit')) {
            $edit = User::find($request->query('edit'));
        }

        $breadcrumbs = [
            ['label' => 'Users']
        ];
        return view('dashboard.users.index', compact('users','breadcrumbs','roles', 'edit'));
    }

    public function update(Request $request, string $id)
    {
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $user = User::findOrFail($id);

        $request->validate([
            'is_verified' => 'required|boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user->is_verified = $request->is_verified;
        $user->save();

        if ($request->has('roles')) {
            $user->syncRoles($request->roles); // remove old roles, assign new
        }

        $edit = null;

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(string $id)
    {
        // Only admin can delete
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        $user = User::findOrFail($id);
        if ($user->email === 'admin@gmail.com') {
            return redirect()->back()->with('error', 'You cannot delete this user.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

}
