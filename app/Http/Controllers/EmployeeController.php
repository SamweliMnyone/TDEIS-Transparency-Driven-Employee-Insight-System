<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Add this line
use App\Models\Skill;
use App\Models\User;
use App\Models\ProjectEmployeeAssignment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EmployeeController extends BaseProfileController
{
    protected $role = 'Employee';
    protected $viewPrefix = 'employee';
    protected $routePrefix = 'employee';


    public function dashboard()
    {


        $user = auth()->user()->load('skills');

        $employees = User::where('id', '!=', auth()->id())
            ->where('role', 'Employee')
            ->with([
                'skills' => function ($query) {
                    $query->whereNotNull('skill_name'); // Only users with skills
                }
            ])
            ->get();

        return view('TDEIS.auth.employee.dashboard', compact('user', 'employees'));
    }

    public function skills()
    {
        $skills = auth()->user()->skills()->paginate(5);
        return view('TDEIS.auth.employee.skills.index', compact('skills'));
    }

    public function storeSkill(Request $request)
    {
        $request->validate([
            'skill_name' => 'required|string|max:255',
            'proficiency' => 'required|in:Beginner,Intermediate,Advanced,Expert',
            'years_of_experience' => 'nullable|integer|min:0',
            'description' => 'nullable|string'
        ]);

        auth()->user()->skills()->create($request->all());

        return redirect()->route('employee.skills')
            ->with('success', 'Skill added successfully!');
    }

    public function updateSkill(Request $request, Skill $skill)
    {
        $this->authorize('update', $skill);

        $request->validate([
            'skill_name' => 'required|string|max:255',
            'proficiency' => 'required|in:Beginner,Intermediate,Advanced,Expert',
            'years_of_experience' => 'nullable|integer|min:0',
            'description' => 'nullable|string'
        ]);

        $skill->update($request->all());

        return redirect()->route('employee.skills')
            ->with('success', 'Skill updated successfully!');
    }

    public function destroySkill(Skill $skill)
    {
        $this->authorize('delete', $skill);

        $skill->delete();

        return redirect()->route('employee.skills')
            ->with('success', 'Skill removed successfully!');
    }

    protected function getProficiencyPercentage($proficiency)
    {
        $levels = [
            'Beginner' => 25,
            'Intermediate' => 50,
            'Advanced' => 75,
            'Expert' => 100
        ];

        return $levels[$proficiency] ?? 0;
    }




    public function myAssignments()
    {
        $user = auth()->user();

        // Get approved assignments
        $approvedAssignments = ProjectEmployeeAssignment::with(['project', 'user'])
            ->where('user_id', $user->id)
            ->where('assignment_status', 'Approved')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Get rejected assignments
        $rejectedAssignments = ProjectEmployeeAssignment::with('project')
            ->where('user_id', $user->id)
            ->where('assignment_status', 'Rejected')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Get current assignments (Active, Pending, Completed)
        $currentAssignments = ProjectEmployeeAssignment::with('project')
            ->where('user_id', $user->id)
            ->whereIn('assignment_status', ['Pending HR Approval', 'Active', 'Completed'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('TDEIS.auth.employee.employee-assignments', compact(
            'approvedAssignments',
            'rejectedAssignments',
            'currentAssignments'
        ));
    }



    public function downloadNotification($assignmentId, $type)
{
    $assignment = ProjectEmployeeAssignment::with(['project', 'user'])
        ->where('id', $assignmentId)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    // Validate the document type
    if (!in_array($type, ['approval', 'rejection'])) {
        abort(404);
    }

    // Validate assignment status matches document type
    if (($type === 'approval' && $assignment->assignment_status !== 'Approved') ||
        ($type === 'rejection' && $assignment->assignment_status !== 'Rejected')) {
        abort(404);
    }

    $pdf = PDF::loadView('TDEIS.auth.employee.assignment-notification', [
        'assignment' => $assignment,
        'type' => $type
    ]);

    $filename = $type === 'approval'
        ? 'approval-letter-'.$assignment->project->name.'.pdf'
        : 'rejection-notice-'.$assignment->project->name.'.pdf';

    return $pdf->download($filename);
}
}
