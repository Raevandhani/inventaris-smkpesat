<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function index()
    {
        // hasRole Is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }
        
        $users = User::latest()->get();
        $breadcrumbs = [
            ['label' => 'Users']
        ];
        return view('dashboard.users.index', compact('users','breadcrumbs'));
    }

    public function update(Request $request, string $id)
    {
        // hasRole Is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }

        //
    }

    public function destroy(string $id)
    {
        // hasRole Is Not A Bug
        if (!Auth::user()->hasRole('admin')) {
            abort(response()->redirectToRoute('dashboard'));
        }
        
        //
    }
}
