<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmployeeDetail;
use App\Models\Skill;
use App\Models\Contribution;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectEmployeeAssignment;
use Illuminate\Support\Facades\Log;
use PhpParser\Builder\Function_;

class HRController extends BaseProfileController
{
    protected $role = 'HR';
    protected $viewPrefix = 'hr';
    protected $routePrefix = 'hr';
    // HR Dashboard
    public function dashboard()
    {
        // Simple counts for dashboard
        $employeeCount = User::where('role', 'employee')->count();
        $newHires = User::where('role', 'employee')
                      ->where('created_at', '>', now()->subDays(30))
                      ->count();
         $employees = \App\Models\User::all();
        return view('TDEIS.auth.HR.dashboard', [
            'employeeCount' => $employeeCount,
            'newHires' => $newHires,
        ]);

    }



    public function assignments()
    {
        $assignments = ProjectEmployeeAssignment::with([
                'project',
                'employee',
                'requiredSkill'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('TDEIS.auth.HR.assignment2', compact('assignments'));
    }

    public function updateAssignment(Request $request, ProjectEmployeeAssignment $assignment)
    {
        $validated = $request->validate([
            'assignment_status' => 'required|in:Pending HR Approval,Approved,Rejected',
            'years_of_experience_needed' => 'nullable|integer|min:0'
        ]);

        $assignment->update($validated);

        return redirect()->route('hr.assignments')
            ->with('success', 'Assignment updated successfully!');
    }

    public function destroyAssignment(ProjectEmployeeAssignment $assignment)
    {
        $assignment->delete();

        return redirect()->route('hr.assignments')
            ->with('success', 'Assignment deleted successfully!');
    }

}
