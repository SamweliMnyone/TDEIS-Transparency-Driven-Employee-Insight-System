<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Schema;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
        })->with('roles')->paginate(10);

        $roles = Role::all();

        return view('TDEIS.auth.admin.permission', compact('users', 'roles', 'search'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        // Remove all current roles and assign the new one
        $user->roles()->detach();
        $user->assignRole($request->role);

        // Update the role field in users table if it exists
        if (Schema::hasColumn('users', 'role')) {
            $user->update(['role' => $request->role]);
        }

        return back()->with('success', 'User role updated successfully!');
    }
}