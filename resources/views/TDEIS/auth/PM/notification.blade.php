@extends('TDEIS.auth.PM.body.app')

@section('yes')
    <title>TDEIS | Project Assignments</title>

    <style>
        .assignment-table {
            font-size: 14px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .status-pending {
            background-color: #fff3cd;
        }
        .status-approved {
            background-color: #d4edda;
        }
        .status-rejected {
            background-color: #f8d7da;
        }
        .action-btns .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .filter-section {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>

    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Project Assignments</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li class="breadcrumb-item"><a href="{{ route('pm.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Project Assignments</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">All Project Assignments</strong>
                        </div>
                        <div class="card-body">
                            <div class="filter-section">
                                <form method="GET" action="">
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="project_id">Project</label>
                                            <select class="form-control" id="project_id" name="project_id">
                                                <option value="">All Projects</option>
                                                @foreach($projects as $project)
                                                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                                        {{ $project->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="employee_id">Employee</label>
                                            <select class="form-control" id="employee_id" name="employee_id">
                                                <option value="">All Employees</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                                        {{ $employee->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="">All Statuses</option>
                                                <option value="Pending HR Approval" {{ request('status') == 'Pending HR Approval' ? 'selected' : '' }}>Pending</option>
                                                <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3 align-self-end">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                            <a href="{{ route('pm.assignments.index') }}" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered assignment-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Project</th>
                                            <th>Employee</th>
                                            <th>Required Skill</th>
                                            <th>Years Needed</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assignments as $assignment)
                                            <tr class="status-{{ strtolower(str_replace(' ', '-', $assignment->assignment_status)) }}">
                                                <td>{{ $assignment->id }}</td>
                                                <td>{{ $assignment->project->name ?? 'N/A' }}</td>
                                                <td>{{ $assignment->employee->name ?? 'N/A' }}</td>
                                                <td>{{ $assignment->requiredSkill->name ?? 'N/A' }}</td>
                                                <td>{{ $assignment->years_of_experience_needed ?? 'N/A' }}</td>
                                                <td>
                                                    @if($assignment->assignment_status == 'Pending HR Approval')
                                                        <span class="badge badge-warning">{{ $assignment->assignment_status }}</span>
                                                    @elseif($assignment->assignment_status == 'Approved')
                                                        <span class="badge badge-success">{{ $assignment->assignment_status }}</span>
                                                    @else
                                                        <span class="badge badge-danger">{{ $assignment->assignment_status }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $assignment->created_at->format('Y-m-d H:i') }}</td>
                                                <td>{{ $assignment->updated_at->format('Y-m-d H:i') }}</td>
                                                <td class="action-btns">
                                                    @if($assignment->assignment_status == 'Pending HR Approval')
                                                        <form action="{{ route('pm.assignments.approve', $assignment->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-success btn-sm" title="Approve">
                                                                <i class="fa fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('pm.assignments.reject', $assignment->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Reject">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('pm.assignments.destroy', $assignment->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this assignment?')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $assignments->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <script>
            $(document).ready(function() {
                toastr.success('{{ session('success') }}');
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            $(document).ready(function() {
                toastr.error('{{ session('error') }}');
            });
        </script>
    @endif
@endsection