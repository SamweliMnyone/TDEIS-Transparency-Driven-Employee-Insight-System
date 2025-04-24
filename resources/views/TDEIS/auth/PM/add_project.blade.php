@extends('TDEIS.auth.PM.body.app')

@section('yes')
    <title>TDEIS | Project Management</title>

    <style>
        .employee-details-card {
            border-left: 4px solid #007bff;
            background-color: #f8f9fa;
        }
        .employee-detail-item {
            margin-bottom: 10px;
        }
        .employee-detail-item strong {
            display: inline-block;
            width: 150px;
        }
        .select2-container--default .select2-results__option--highlighted {
            background-color: #f8f9fa !important;
            color: #333 !important;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #e9ecef !important;
        }
        .match-percentage-bar {
            height: 20px;
            background-color: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
        }
        .match-percentage-fill {
            height: 100%;
            background-color: #28a745;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }
        .employee-skill-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .skill-name {
            font-weight: 600;
        }
        .skill-match {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .employee-card {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .employee-card:hover {
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .employee-header {
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
        }
        .employee-body {
            padding: 15px;
        }
        .no-employees {
            text-align: center;
            padding: 20px;
            color: #6c757d;
        }
    </style>

    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Project Management</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li class="breadcrumb-item"><a href="{{ route('pm.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Projects</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="animated fadeIn">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Error Message --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Projects</strong>
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addProjectModal">
                                <i class="fa fa-plus"></i> Add New Project
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Objective</th>
                                            <th>Scope</th>
                                            <th>Estimated Time (Months)</th>
                                            <th>Estimated Cost (TZS)</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $i = ($projects->currentPage() - 1) * $projects->perPage() + 1;
                                        @endphp
                                        @forelse ($projects as $project)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $project->name }}</td>
                                                <td>{{ $project->objective }}</td>
                                                <td>{{ $project->scope }}</td>
                                                <td>{{ $project->estimated_time ? $project->estimated_time . ' Months' : 'N/A' }}</td>
                                                <td>{{ $project->estimated_cost ? number_format($project->estimated_cost, 2) . ' TZS' : 'N/A' }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#assignEmployeesModal{{ $project->id }}">
                                                        <i class="fa fa-users"></i> Assign
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editProjectModal{{ $project->id }}">
                                                        <i class="fa fa-pencil"></i> Edit
                                                    </button>
                                                    <form action="{{ route('pm.projects.destroy', $project->id) }}" method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-sm btn-danger delete-button" data-project-id="{{ $project->id }}">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>

                                            {{-- Edit Project Modal --}}
                                            <div class="modal fade" id="editProjectModal{{ $project->id }}" tabindex="-1" role="dialog" aria-labelledby="editProjectModalLabel{{ $project->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editProjectModalLabel{{ $project->id }}">Edit Project</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('pm.projects.update', $project->id) }}" method="POST" class="form-horizontal">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body card-block">
                                                                <div class="row form-group">
                                                                    <div class="col col-md-3"><label for="edit_name" class=" form-control-label">Project Name</label></div>
                                                                    <div class="col-12 col-md-9"><input type="text" id="edit_name" name="name" value="{{ $project->name }}" placeholder="Enter project name" class="form-control" required></div>
                                                                </div>
                                                                <div class="row form-group">
                                                                    <div class="col col-md-3"><label for="edit_objective" class=" form-control-label">Project Objective</label></div>
                                                                    <div class="col-12 col-md-9"><textarea name="objective" id="edit_objective" rows="5" placeholder="Describe the main objective of the project" class="form-control" required>{{ $project->objective }}</textarea></div>
                                                                </div>
                                                                <div class="row form-group">
                                                                    <div class="col col-md-3"><label for="edit_scope" class=" form-control-label">Project Scope</label></div>
                                                                    <div class="col-12 col-md-9"><textarea name="scope" id="edit_scope" rows="5" placeholder="Outline the scope of the project" class="form-control" required>{{ $project->scope }}</textarea></div>
                                                                </div>
                                                                <div class="row form-group">
                                                                    <div class="col col-md-3"><label for="edit_time" class=" form-control-label">Estimated Time (Months)</label></div>
                                                                    <div class="col-12 col-md-9"><input type="text" id="edit_time" name="time" value="{{ $project->estimated_time }}" placeholder="Enter estimated time in months" class="form-control"></div>
                                                                </div>
                                                                <div class="row form-group">
                                                                    <div class="col col-md-3"><label for="edit_cost" class=" form-control-label">Estimated Cost (TZS)</label></div>
                                                                    <div class="col-12 col-md-9"><input type="number" id="edit_cost" name="cost" value="{{ $project->estimated_cost }}" placeholder="Enter estimated cost in TZS" class="form-control"></div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Update Project</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

<!-- Assign Employees Modal -->
<div class="modal fade" id="assignEmployeesModal{{ $project->id }}" tabindex="-1" role="dialog" aria-labelledby="assignEmployeesModalLabel{{ $project->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Assign Employee to: {{ $project->name }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('assign.employee', $project->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="employee_id">Select Employee:</label>
                        <select name="employee_id" id="employee_id" class="form-control" required>
                            <option value="">-- Select Employee --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    data-skills="{{ $employee->skills->map(function($skill) {
                                        return [
                                            'id' => $skill->id,
                                            'skill_name' => $skill->skill_name,
                                            'years_of_experience' => $skill->pivot->years_of_experience,
                                            'match_percentage' => min(100, $skill->pivot->years_of_experience * 20)
                                        ];
                                    })->toJson() }}">
                                    {{ $employee->name }} - {{ $employee->position }}
                                    @if($employee->skills->isNotEmpty())
                                        @php
                                            $bestSkill = $employee->skills->sortByDesc(function($skill) {
                                                return $skill->pivot->years_of_experience;
                                            })->first();
                                        @endphp
                                        (Best skill: {{ $bestSkill->skill_name }} - {{ min(100, $bestSkill->pivot->years_of_experience * 20) }}%)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="employeeSkillsContainer" class="mt-4" style="display: none;">
                        <h5>Employee Skills:</h5>
                        <div id="skillsList" class="mb-3"></div>

                        <div class="form-group">
                            <label for="required_skill">Select Required Skill for Project:</label>
                            <select name="required_skill" id="required_skill" class="form-control" required>
                                <option value="">-- Select Skill --</option>
                                @foreach($skills as $skill)
                                    <option value="{{ $skill->id }}">{{ $skill->skill_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="years_of_experience_needed">Years of Experience Needed:</label>
                            <input type="number" name="years_of_experience_needed" id="years_of_experience_needed" class="form-control" min="0" placeholder="Enter required years of experience">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

                                        @empty
                                            <tr><td colspan="6" class="text-center">No projects found.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add New Project Modal --}}
    <div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="addProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProjectModalLabel">Add New Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('pm.projects.store') }}" method="POST" class="form-horizontal">
                    @csrf
                    <div class="modal-body card-block">
                        <div class="row form-group">
                            <div class="col col-md-3"><label for="name" class=" form-control-label">Project Name</label></div>
                            <div class="col-12 col-md-9"><input type="text" id="name" name="name" placeholder="Enter project name" class="form-control" required></div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3"><label for="objective" class=" form-control-label">Project Objective</label></div>
                            <div class="col-12 col-md-9"><textarea name="objective" id="objective" rows="5" placeholder="Describe the main objective of the project" class="form-control" required></textarea></div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3"><label for="scope" class=" form-control-label">Project Scope</label></div>
                            <div class="col-12 col-md-9"><textarea name="scope" id="scope" rows="5" placeholder="Outline the scope of the project" class="form-control" required></textarea></div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3"><label for="time" class=" form-control-label">Estimated Time (Months)</label></div>
                            <div class="col-12 col-md-9"><input type="text" id="time" name="time" placeholder="Enter estimated time in months" class="form-control"></div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3"><label for="cost" class=" form-control-label">Estimated Cost (TZS)</label></div>
                            <div class="col-12 col-md-9"><input type="number" id="cost" name="cost" placeholder="Enter estimated cost in TZS" class="form-control"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Project</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Delete confirmation
            const deleteButtons = document.querySelectorAll('.delete-button');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const projectId = this.dataset.projectId;
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = this.closest('.delete-form');
                            form.submit();
                        }
                    });
                });
            });

            // Employee skills display
            $('[id^=employee_id]').change(function() {
                const selectedOption = $(this).find('option:selected');
                const skills = selectedOption.data('skills');
                const skillsList = $(this).closest('.modal-content').find('#skillsList');
                const container = $(this).closest('.modal-content').find('#employeeSkillsContainer');

                if (skills && skills.length > 0) {
                    skillsList.empty();

                    skills.forEach(skill => {
                        const years = skill.years_of_experience ?? 0;
                        const percentage = Math.min(100, years * 20);

                        skillsList.append(`
                            <div class="employee-skill-item">
                                <span class="skill-name">${skill.skill_name}</span>
                                <div class="skill-match">
                                    <span>${percentage}% match</span>
                                    <div class="match-percentage-bar" style="width: 150px;">
                                        <div class="match-percentage-fill" style="width: ${percentage}%">${percentage}%</div>
                                    </div>
                                </div>
                            </div>
                        `);
                    });

                    container.show();
                } else {
                    skillsList.html('<div class="alert alert-warning">This employee has no skills recorded.</div>');
                    container.show();
                }
            });

            // Show SweetAlert for success/error messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@endsection
