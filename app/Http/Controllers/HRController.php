<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmployeeDetail;
use App\Models\Skill;
use App\Models\Contribution;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProjectEmployeeAssignment;
use Illuminate\Support\Facades\Log;
use PhpParser\Builder\Function_;

class HRController extends BaseProfileController
{


    protected $role = 'HR';
    protected $viewPrefix = 'hr';
    protected $routePrefix = 'hr';
    // HR Dashboard
   // Dashboard
   public function dashboard()
   {
       // Get counts for dashboard cards
       $totalEmployees = User::where('role', 'Employee')->count();
       $totalProjects = Project::count();
       $activeAssignments = ProjectEmployeeAssignment::where('assignment_status', 'Approved')->count();
       $pendingApprovals = ProjectEmployeeAssignment::where('assignment_status', 'Pending HR Approval')->count();

       // Get recent activities
       $recentProjects = Project::latest()->take(5)->get();

       // Corrected eager loading: use 'employee' instead of 'user'
       $recentAssignments = ProjectEmployeeAssignment::with(['project', 'employee'])  // Change 'user' to 'employee'
           ->latest()
           ->take(5)
           ->get();

       // Get skill distribution
       $skillDistribution = Skill::selectRaw('skill_name, COUNT(*) as count')
           ->groupBy('skill_name')
           ->orderBy('count', 'desc')
           ->take(10)
           ->get();

       // Define userCounts for employees
       $userCounts = [
           'employees' => $totalEmployees,
           'projects' => $totalProjects,
           'active_assignments' => $activeAssignments,
           'pending_approvals' => $pendingApprovals,
       ];

       return view('TDEIS.auth.HR.dashboard', compact(
           'totalEmployees',
           'totalProjects',
           'activeAssignments',
           'pendingApprovals',
           'recentProjects',
           'recentAssignments',
           'skillDistribution',
           'userCounts'
       ));
   }



   // Assignments Management
   public function assignments2()
   {
       $assignments = ProjectEmployeeAssignment::with(['project', 'user'])
           ->orderBy('created_at', 'desc')
           ->paginate(10);

       return view('TDEIS.auth.HR.assignment2', compact('assignments'));
   }

   // Update Assignment Status
   public function updateAssignment(Request $request, ProjectEmployeeAssignment $assignment)
   {
       $validated = $request->validate([
           'assignment_status' => 'required|in:Pending HR Approval,Approved,Rejected,Active,Completed,Removed'
       ]);

       $assignment->update($validated);

       return back()->with('success', 'Assignment status updated successfully.');
   }

   // Delete Assignment
   public function destroyAssignment(ProjectEmployeeAssignment $assignment)
   {
       $assignment->delete();
       return back()->with('success', 'Assignment deleted successfully.');
   }

   // Generate Reports
   public function generateReport(Request $request)
   {
       $request->validate([
           'report_type' => 'required|in:employees,projects,assignments',
           'format' => 'required|in:pdf',
           'start_date' => 'nullable|date',
           'end_date' => 'nullable|date|after_or_equal:start_date'
       ]);

       $type = $request->input('report_type');
       $startDate = $request->input('start_date');
       $endDate = $request->input('end_date');

       $data = [];
       $title = '';
       $view = '';
       $compactData = ['data', 'title']; // Initialize compact data array

       switch ($type) {
           case 'employees':
               $data['employees'] = User::where('role', 'Employee')->with('skills')->get();
               $title = 'Employee Skills Report';
               $view = 'TDEIS.auth.HR.reports.employees';
               break;

           case 'projects':
               $data['projects'] = Project::with(['manager', 'assignments.employee'])->get();
               $title = 'Projects Report';
               $view = 'TDEIS.auth.HR.reports.projects';
               break;

           case 'assignments':
               $query = ProjectEmployeeAssignment::with(['project', 'employee']);
               if ($startDate && $endDate) {
                   $query->whereBetween('created_at', [$startDate, $endDate]);
               }
               $data['assignments'] = $query->get();
               $title = 'Assignments Report';
               $view = 'TDEIS.auth.HR.reports.assignments';
               // Add startDate and endDate to the compact data for assignments
               $compactData = ['data', 'title', 'startDate', 'endDate'];
               break;

           default:
               return back()->with('error', 'Invalid report type selected.');
       }

       $pdf = Pdf::loadView($view, compact($compactData));
       return $pdf->download(Str::slug($title) . '_' . now()->format('Y-m-d') . '.pdf');
   }

   public function employeeskills()
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

       return view('TDEIS.auth.HR.employees&kills', compact('projects', 'employees', 'user', 'topExperts', 'activeAssignmentCount'));
   }

}
