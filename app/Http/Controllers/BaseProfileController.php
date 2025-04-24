<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use App\Models\Project;



class BaseProfileController extends Controller
{
    use AuthorizesRequests;

    protected $role;
    protected $viewPrefix;
    protected $routePrefix;

    public function profile()
    {

        $user = auth()->user();
        return view("TDEIS.auth.{$this->viewPrefix}.profile", compact('user'));
    }

    public function updateProfile(Request $request)
    {

        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if any changes were made
        $changesMade = false;
        $originalData = $user->only(['name', 'email', 'phone', 'date_of_birth', 'gender']);
        $newData = $request->only(['name', 'email', 'phone', 'date_of_birth', 'gender']);

        if ($originalData != $newData) {
            $changesMade = true;
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $changesMade = true;
            // Delete old profile photo if exists
            if ($user->profile_picture) {
                Storage::delete($user->profile_picture);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_picture = $path;
        }

        if (!$changesMade) {
            return redirect()->back()->with('warning', 'No changes were made to your profile.');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->date_of_birth = $request->date_of_birth;
        $user->gender = $request->gender;
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|different:current_password',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'passwordErrors')
                ->withInput();
        }

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    public function deleteAccount(Request $request)
    {

        // Check if user has permission to delete
        if (!auth()->user()->can('delete_post')) {
            return back()->with('error', 'You do not have permission to delete Account.');
        }
        // Validate password confirmation
        $validator = Validator::make($request->all(), [
            'confirm_password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'deleteErrors')
                ->withInput();
        }

        $user = auth()->user();

        // Verify password
        if (!Hash::check($request->confirm_password, $user->password)) {
            return redirect()->back()->with('error', 'Password is incorrect.');
        }

        // First delete related records to avoid foreign key errors
        DB::table('permission_user')->where('user_id', $user->id)->delete(); // Remove permission associations
        DB::table('skills')->where('user_id', $user->id)->delete(); // Delete user's skills

        // Delete profile photo if exists
        if ($user->profile_picture) {
            Storage::delete($user->profile_picture);
        }

        // Finally delete the user
        $user->delete();

        // Logout and redirect
        auth()->logout();
        return redirect('/')->with('success', 'Your account has been permanently deleted.');
    }
}
