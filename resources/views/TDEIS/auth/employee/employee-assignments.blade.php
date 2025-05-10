@extends('TDEIS.auth.employee.body.app')

@section('content')
    <title>TDEIS | My Project Assignments</title>

    <style>
        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }
        .badge-approved {
            background-color: #28a745;
            color: white;
        }
        .badge-rejected {
            background-color: #dc3545;
            color: white;
        }
        .badge-active {
            background-color: #17a2b8;
            color: white;
        }
        .badge-completed {
            background-color: #6c757d;
            color: white;
        }
        .notification-card {
            border-left: 4px solid #dc3545;
            margin-bottom: 20px;
        }
        .approved-card {
            border-left: 4px solid #28a745;
            margin-bottom: 20px;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
        }
    </style>

    <div class="breadcrumbs">
        <!-- Breadcrumbs remain the same as before -->
    </div>

    <div class="content">
        <div class="animated fadeIn">
            @if(session('success'))
                <!-- Success message remains the same -->
            @endif

            <!-- Approved Assignments -->
            @if($approvedAssignments->isNotEmpty())
                <div class="row">
                    <div class="col-md-12">
                        <div class="card approved-card">
                            <div class="card-header bg-white">
                                <strong class="card-title text-success">Approved Assignments</strong>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Project</th>
                                                <th>Required Skill</th>
                                                <th>Approved On</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($approvedAssignments as $assignment)
                                            <tr>
                                                <td>{{ $assignment->project->name ?? 'N/A' }}</td>
                                                <td>{{ $assignment->requiredSkill->skill_name ?? 'N/A' }}</td>
                                                <td>{{ $assignment->updated_at->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="{{ route('employee.assignments.download-notification', ['assignment' => $assignment->id, 'type' => 'approval']) }}"
                                                           class="btn btn-sm btn-outline-success">
                                                            <i class="fa fa-download"></i> Approval Letter
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Rejected Assignments -->
            @if($rejectedAssignments->isNotEmpty())
                <div class="row">
                    <div class="col-md-12">
                        <div class="card notification-card">
                            <div class="card-header bg-white">
                                <strong class="card-title text-danger">Rejected Assignments</strong>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Project</th>
                                                <th>Required Skill</th>
                                                <th>Rejected On</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rejectedAssignments as $assignment)
                                            <tr>
                                                <td>{{ $assignment->project->name ?? 'N/A' }}</td>
                                                <td>{{ $assignment->required_skill }}</td>
                                                <td>{{ $assignment->updated_at->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="{{ route('employee.assignments.download-notification', ['assignment' => $assignment->id, 'type' => 'rejection']) }}"
                                                           class="btn btn-sm btn-outline-danger">
                                                            <i class="fa fa-download"></i> Rejection Notice
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Current Assignments (Active/Pending/Completed) -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">My Current Assignments</strong>
                        </div>
                        <div class="card-body">
                            @if($currentAssignments->isEmpty())
                                <div class="alert alert-info">
                                    You currently have no active project assignments.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Project</th>
                                                <th>Required Skill</th>
                                                <th>Status</th>
                                                <th>Assigned On</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($currentAssignments as $assignment)
                                            <tr>
                                                <td>{{ $assignment->project->name ?? 'N/A' }}</td>
                                                <td>{{ $assignment->required_skill }}</td>
                                                <td>
                                                    <span class="badge badge-{{ strtolower(str_replace(' ', '-', $assignment->assignment_status)) }}">
                                                        {{ $assignment->assignment_status }}
                                                    </span>
                                                </td>
                                                <td>{{ $assignment->created_at->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    @if($assignment->assignment_status === 'Approved')
                                                        <a href="{{ route('employee.assignments.download-notification', ['assignment' => $assignment->id, 'type' => 'approval']) }}"
                                                           class="btn btn-sm btn-outline-success">
                                                            <i class="fa fa-download"></i> Letter
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
