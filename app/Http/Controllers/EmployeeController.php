<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Add this line
use App\Models\Skill;
use App\Models\User;
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
}