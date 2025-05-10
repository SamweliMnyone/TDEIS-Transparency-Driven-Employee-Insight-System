<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use RealRashid\SweetAlert\Facades\SweetAlert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class AdminController extends BaseProfileController
{
    protected $role = 'ADMIN';
    protected $viewPrefix = 'admin';
    protected $routePrefix = 'admin';

    public function dashboard()
    {
        $userCounts = [
            'admins' => User::where('role', 'ADMIN')->count(),
            'employees' => User::where('role', 'Employee')->count(),
            'pms' => User::where('role', 'PM')->count(),
            'hrs' => User::where('role', 'HR')->count()
        ];

        return view('TDEIS.auth.admin.dashboard', compact('userCounts'));
    }

    public function manage_users(Request $request, $userType)
    {

        // 2. Validate user type parameter
        $validTypes = ['Employee', 'HR', 'PM', 'ADMIN'];
        if (!in_array($userType, $validTypes)) {
            abort(404, 'Invalid user type specified');
        }

        // 3. Process the request (search, pagination, etc.)
        $search = $request->input('search');

        $users = User::where('role', $userType)
            ->when($search, function($query) use ($search) {
                return $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            })
            ->orderBy('name')
            ->paginate(10);

        // 4. Get permissions (only if we passed the initial checks)
        $allPermissions = Permission::orderBy('name')->get();

        // 5. Return response
        if ($request->ajax()) {
            return response()->json([
                'table' => view('TDEIS.auth.admin.partials.user_table', [
                    'users' => $users,
                    'userType' => $userType,
                    'canEdit' => auth()->user()->can('edit-users'),
                    'canDelete' => auth()->user()->can('delete-users'),
                    'canAssignPermissions' => auth()->user()->can('assign-permissions')
                ])->render(),
                'pagination' => $users->appends(['search' => $search])
            ]);
        }

        // 6. Return full view with all permission checks
        return view('TDEIS.auth.admin.manage_users', [
            'users' => $users,
            'userType' => $userType,
            'allPermissions' => $allPermissions,
            'canEdit' => auth()->user()->can('edit-users'),
            'canDelete' => auth()->user()->can('delete-users'),
            'canAssignPermissions' => auth()->user()->can('assign-permissions')
        ]);
    }
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $originalData = $user->toArray();
        $data = $request->all();

        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if any data was actually changed
        $changes = false;
        foreach ($data as $key => $value) {
            if (!array_key_exists($key, $originalData)) {
                continue;
            }

            if ($originalData[$key] != $value) {
                $changes = true;
                break;
            }
        }

        if (!$changes && !$request->hasFile('profile_picture')) {
            return redirect()->route('manage.users', ['type' => $user->role])
                ->with('warning', 'No modifications were made to the user data.');
        }

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                \Storage::delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $data['profile_picture'] = $path;
        }

        $user->update($data);

        // Redirect back to the same user type page
        return redirect()->route('manage.users', ['type' => $user->role])
            ->with('success', ucfirst($user->role).' updated successfully!');
    }

    public function deleteUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $userType = $user->role;

        // Delete related records first
        DB::table('permission_user')->where('user_id', $user->id)->delete();

        // Delete profile picture if exists
        if ($user->profile_picture) {
            \Storage::delete($user->profile_picture);
        }

        $user->delete();

        // Get redirect path from hidden input or default to user type list
        $redirectTo = $request->input('redirect_to') ?? route('manage.users', ['type' => strtolower($userType)]);

        return redirect($redirectTo)
            ->with('success', ucfirst($userType).' deleted successfully!');
    }

    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female',
            'role' => 'required|in:Employee,HR,PM,ADMIN' // Now passed from the form
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except(['profile_picture']);
        $data['password'] = bcrypt($request->password);

        User::create($data);

        return redirect()->route('manage.users', ['type' => $request->role])
            ->with('success', ucfirst($request->role).' created successfully!');
    }
}
