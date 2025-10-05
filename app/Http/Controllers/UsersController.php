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
        
        $query = User::with('roles');
        $roles = Role::all();

        if ($request->has('search') && $request->has('sort')) {
            if (trim($request->query('search', '')) === '' && trim($request->query('sort', '')) === '') {
                return redirect()->route('users.index');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('roles', fn($r) => $r->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('filter')) {
            if ($request->filter === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->filter === 'not_verified') {
                $query->where(function($q) {
                    $q->where('is_verified', false)
                      ->orWhereNull('is_verified');
                });
            }
        }

        $query->orderBy('id', 'desc');

        $n = 6;

        $users = $query->paginate($n)->appends($request->all());

        $edit = null;
        if ($request->has('edit')) {
            $edit = User::find($request->query('edit'));
        }

        $breadcrumbs = [
            ['label' => 'Users']
        ];

        return view('dashboard.users.index', compact('users', 'breadcrumbs', 'roles', 'edit','n'));
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
            $user->syncRoles($request->roles);
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
