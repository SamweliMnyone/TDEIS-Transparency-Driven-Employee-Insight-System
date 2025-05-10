<?php

namespace App\Http\Controllers;

use App\Models\Project;

use App\Models\User;
use App\Models\ProjectEmployeeAssignment;
use App\Models\Skill; // Add this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Import the Log class

class PMController extends BaseProfileController
{
    protected $role = 'PM';
    protected $viewPrefix = 'PM';
    protected $routePrefix = 'pm';

    public function dashboard()
    {
        $user = auth()->user()->load('skills');

        $employees = User::where('id', '!=', auth()->id())
            ->where('role', 'Employee')
            ->with([
                'skills' => function ($query) {
                    $query->whereNotNull('skill_name');
                }
            ])
            ->get();

        $projects = Project::all();

        // Get top 5 experts
        $topExperts = User::where('role', 'Employee')
            ->with(['skills' => function($query) {
                $query->where('proficiency', 'Advanced')
                      ->orWhere('proficiency', 'Expert')
                      ->orderBy('years_of_experience', 'desc');
            }])
            ->get()
            ->sortByDesc(function($user) {
                return $user->skills->count();
            })
            ->take(5);

        // Get the count of active assignments
        $activeAssignmentCount = \App\Models\ProjectEmployeeAssignment::where('assignment_status', 'Active')->count();

        return view('TDEIS.auth.PM.dashboard', compact('projects', 'employees', 'user', 'topExperts', 'activeAssignmentCount'));
    }



    public function projects(Request $request)
    {
        $projects = Project::where('project_manager_id', auth()->id())
            ->latest()
            ->paginate(perPage: 10);

        $skills = Skill::orderBy('skill_name')->get();

        // Get all employee IDs already assigned to any project
        $assignedEmployeeIds = ProjectEmployeeAssignment::pluck('user_id')->toArray();

        // Get available employees with their skills
        $employees = User::where('role', 'Employee')
            ->whereNotIn('id', $assignedEmployeeIds)
            ->with(['skills' => function($query) {
                $query->select('skills.id', 'skill_name', 'years_of_experience');
            }])
            ->get();

        return view('TDEIS.auth.PM.add_project', compact('projects', 'skills', 'employees'));
    }

    public function assignEmployeeToProject(Request $request, Project $project)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'required_skill' => 'required|exists:skills,id',
            'years_of_experience_needed' => 'nullable|integer|min:0'
        ]);

        // Check if employee is already assigned to this project
        $existing = ProjectEmployeeAssignment::where([
            'project_id' => $project->id,
            'user_id' => $request->employee_id
        ])->exists();

        if ($existing) {
            return redirect()->back()->with('error', 'Employee already assigned to this project');
        }

        // Get the employee's skill to calculate match percentage
        $employee = User::with(['skills' => function($query) use ($request) {
            $query->where('skills.id', $request->required_skill);
        }])->find($request->employee_id);

        $skillMatch = 0;
        if ($employee->skills->isNotEmpty()) {
            $skill = $employee->skills->first();

        }

        // Create the assignment
        ProjectEmployeeAssignment::create([
            'project_id' => $project->id,
            'user_id' => $request->employee_id,
            'required_skill' => $request->required_skill,
            'years_of_experience_needed' => $request->years_of_experience_needed,
            'skill_match_percentage' => $skillMatch,
            'assignment_status' => 'Pending HR Approval'
        ]);

        return redirect()->back()->with('success', 'Employee assigned successfully ');
    }

    public function storeProject(Request $request)
    {
        // Validate the incoming data for adding a project
        $request->validate([
            'name' => 'required|string|max:255',
            'objective' => 'required|string',
            'scope' => 'required|string',
            'time' => 'nullable|string|max:50',
            'cost' => 'nullable|numeric',
        ]);

        // Create a new project
        Project::create([
            'name' => $request->name,
            'objective' => $request->objective,
            'scope' => $request->scope,
            'estimated_time' => $request->time,
            'estimated_cost' => $request->cost,
            'project_manager_id' => auth()->id(),
        ]);

        // Flash a success message
        session()->flash('success', 'Project created successfully!');

        // Redirect back to the projects page
        return redirect()->route('pm.projects');
    }

    public function updateProject(Request $request, Project $project)
    {
        // Authorize that the logged-in user owns this project (optional, but recommended)
        if ($project->project_manager_id !== auth()->id()) {
            session()->flash('error', 'You are not authorized to edit this project.');
            return redirect()->route('pm.projects');
        }

        // Validate the incoming data for editing a project
        $request->validate([
            'name' => 'required|string|max:255',
            'objective' => 'required|string',
            'scope' => 'required|string',
            'time' => 'nullable|string|max:50',
            'cost' => 'nullable|numeric',
        ]);
        try {
            // Update the project
            $project->update([
                'name' => $request->name,
                'objective' => $request->objective,
                'scope' => $request->scope,
                'estimated_time' => $request->time,
                'estimated_cost' => $request->cost,
            ]);

            // Flash a success message
            session()->flash('success', 'Project updated successfully!');

            // Redirect back to the projects page
            return redirect()->route('pm.projects');
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'out of range value for column \'estimated_cost\'')) {
                session()->flash('error', 'The estimated cost you entered is too large. Please enter a smaller numeric value.');
            } else {
                // Handle other database errors if needed, or provide a generic message
                session()->flash('error', 'An unexpected database error occurred. Please try again.');
            }
            return redirect()->back()->withInput(); // Redirect back with the user's input
        }
        // The following lines are redundant after the try-catch block
        // session()->flash('success', 'Project updated successfully!');
        // return redirect()->route('pm.projects');
    }

    public function destroyProject(Project $project)
    {
        // Authorize that the logged-in user owns this project (optional, but recommended)
        if ($project->project_manager_id !== auth()->id()) {
            session()->flash('error', 'You are not authorized to delete this project.');
            return redirect()->route('pm.projects');
        }

        // Delete the project
        $project->delete();

        // Flash a success message
        session()->flash('success', 'Project deleted successfully!');

        // Redirect back to the projects page
        return redirect()->route('pm.projects');
    }

    // New method to check for and return flash messages
    public function checkFlashMessages(Request $request)
    {
        $success = $request->session()->get('success');
        $error = $request->session()->get('error');

        $request->session()->forget('success'); // Clear the message after reading
        $request->session()->forget('error');

        return response()->json(['success' => $success, 'error' => $error]);
    }



    public function getEmployeesBySkill(Request $request, $projectId, $skillId)
    {
        $project = Project::findOrFail($projectId);
        $skill = Skill::findOrFail($skillId);

        // Get employees with the given skill and their proficiency, ordered by years of experience
        $employees = User::whereHas('skills', function($query) use ($skillId) {
            $query->where('skill_id', $skillId);
        })
        ->with(['skills' => function($query) use ($skillId) {
            $query->where('skill_id', $skillId)
                  ->select('skills.id', 'skills.name', 'skill_user.proficiency_level', 'skill_user.years_of_experience');
        }])
        ->orderByDesc(function($query) use ($skillId) {
            $query->select('years_of_experience')
                  ->from('skill_user')
                  ->whereColumn('skill_user.user_id', 'users.id')
                  ->where('skill_id', $skillId)
                  ->limit(1);
        })
        ->get();

        // Calculate match percentage
        $projectSkills = $project->skills->pluck('id')->toArray();

        $employeesWithMatch = $employees->map(function($employee) use ($projectSkills, $skillId) {
            $employeeSkills = $employee->skills->pluck('id')->toArray();
            $matchingSkills = count(array_intersect($projectSkills, $employeeSkills));
            $totalProjectSkills = count($projectSkills);
            $matchPercentage = ($totalProjectSkills > 0) ? ($matchingSkills / $totalProjectSkills) * 100 : 0;

            $skillDetails = $employee->skills->firstWhere('id', $skillId);

            return [
                'id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email,
                'match_percentage' => round($matchPercentage, 2),
                'proficiency_level' => $skillDetails->pivot->proficiency_level ?? 'Unknown',
                'years_of_experience' => $skillDetails->pivot->years_of_experience ?? 0
            ];
        });

        // Filter out already assigned employees
        $assignedEmployeeIds = ProjectEmployeeAssignment::where('project_id', $projectId)
            ->where('user_id', '!=', auth()->id()) // Exclude PM from assignments
            ->pluck('user_id')
            ->toArray();

        $availableEmployees = $employeesWithMatch->reject(function($employee) use ($assignedEmployeeIds) {
            return in_array($employee['id'], $assignedEmployeeIds);
        });

        return response()->json($availableEmployees->values()->all());
    }


    public function removeEmployeeFromProject(ProjectEmployeeAssignment $assignment)
    {
        // You might want to add authorization checks here to ensure the logged-in user can remove this assignment

        if ($assignment->assignment_status === 'Pending HR Approval') {
            $assignment->delete();
            return back()->with('success', 'Pending assignment removed successfully.');
        } else {
            return back()->with('error', 'Cannot remove assignment that is not pending HR approval.');
        }
    }

    public function assignments()
    {
        $assignments = ProjectEmployeeAssignment::with([
                'project',
                'employee',
                'requiredSkill'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('TDEIS.auth.PM.notification', compact('assignments'));
    }

    public function updateAssignment(Request $request, ProjectEmployeeAssignment $assignment)
    {
        $validated = $request->validate([
            'assignment_status' => 'required|in:Pending HR Approval,Approved,Rejected',
            'years_of_experience_needed' => 'nullable|integer|min:0'
        ]);

        $assignment->update($validated);

        return redirect()->route('pm.assignments')
            ->with('success', 'Assignment updated successfully!');
    }

    public function destroyAssignment(ProjectEmployeeAssignment $assignment)
    {
        $assignment->delete();

        return redirect()->route('pm.assignments')
            ->with('success', 'Assignment deleted successfully!');
    }

}
