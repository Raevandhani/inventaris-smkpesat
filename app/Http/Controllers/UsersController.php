<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        $breadcrumbs = [
            ['label' => 'Users']
        ];
        return view('dashboard.users.index', compact('users','breadcrumbs'));
    }

    public function students()
    {
        $role = Role::find(1)->name;
        $students = User::role($role)->get();
        $breadcrumbs = [
            ['label' => 'Users', 'url' => route('users.index')],
            ['label' => 'Students']
        ];
        return view('dashboard.users.students.index', compact('students','breadcrumbs'));
    }

    public function teachers()
    {
        $role = Role::find(2)->name;

        // trying_to_fix = role "teacher" undefine
        // hours_wasted = 40+ 

        $teachers = User::role($role)->get();
        $breadcrumbs = [
            ['label' => 'Users', 'url' => route('users.index')],
            ['label' => 'Teachers']
        ];
        return view('dashboard.users.teachers.index', compact('teachers','breadcrumbs'));
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
