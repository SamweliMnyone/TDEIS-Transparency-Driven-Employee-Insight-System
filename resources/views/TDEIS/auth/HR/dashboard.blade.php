@extends('TDEIS.auth.HR.body.app')

@section('yes')
    <title>TDEIS | HR Employee & Skills</title>

    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Dashboard</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="animated fadeIn">
            <!-- Dashboard Cards -->
            <div class="row">

                <!-- Employee Count -->
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="stat-widget-five">
                                <div class="stat-icon dib flat-color-1">
                                    <i class="pe-7s-users"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="text-left dib">
                                        <div class="stat-text">
                                            <span class="count">{{ $totalEmployees }}</span>
                                        </div>
                                        <div class="stat-heading">Employees</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="stat-widget-five">
                                <div class="stat-icon dib flat-color-1">
                                    <i class="fa fa-project-diagram "></i>
                                </div>
                                <div class="stat-content">
                                    <div class="text-left dib">
                                        <div class="stat-text">
                                            <span class="count">{{ $totalProjects }}</span>
                                        </div>
                                        <div class="stat-heading">Total Projects</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="stat-widget-five">
                                <div class="stat-icon dib flat-color-3">
                                    <i class="fa fa-tasks "></i>
                                </div>
                                <div class="stat-content">
                                    <div class="text-left dib">
                                        <div class="stat-text">
                                            <span class="count">{{ $activeAssignments }}</span>
                                        </div>
                                        <div class="stat-heading">Active Assignments</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="stat-widget-five">
                                <div class="stat-icon dib flat-color-2">
                                        <i class="fa fa-clock"></i>
                                </div>
                                <div class="stat-content">
                                        <div class="text-left dib">
                                            <div class="stat-text">
                                                <span class="count">{{ $pendingApprovals }}</span>
                                            </div>
                                            <div class="stat-heading"> Pending Approvals</div>
                                        </div>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>


                    <div class="row">
                        <div class="col-lg-12 col-md-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Recent Projects</h6>
                                    <form method="POST" action="{{ route('hr.report.generate') }}" target="_blank">
                                        @csrf
                                        <input type="hidden" name="report_type" value="projects">
                                        <input type="hidden" name="format" value="pdf">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-file-pdf"></i> Generate PDF
                                        </button>
                                    </form>
                                </div>
                                <div class="row">
                                    @forelse($recentProjects as $project)
                                    <div class="col-md-6 mb-4">
                                        <div class="card h-100 shadow-sm border-left-primary">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 font-weight-bold text-primary">{{ $project->name }}</h6>
                                                <small class="text-muted">{{ $project->created_at->format('M d, Y') }}</small>
                                            </div>
                                            <div class="card-body">

                                                <p><strong>Manager:</strong> {{ $project->manager ? $project->manager->name : 'N/A' }}</p>
                                                <p><strong>Cost:</strong> TZS {{ number_format($project->estimated_cost, 2) }}</p>
                                                <p><strong>Start Date:</strong> {{ $project->created_at ? $project->created_at->format('M d, Y') : 'N/A' }}</p>
                                                </p>
                                                <p><strong>Description:</strong> {{ Str::limit($project->objective, 100, '...') }}</p>
                                            </div>

                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-info">No recent projects found.</div>
                                    </div>
                                @endforelse
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-12 col-md-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Recent Assignments</h6>
                                    <form method="POST" action="{{ route('hr.report.generate') }}" target="_blank">
                                        @csrf
                                        <input type="hidden" name="report_type" value="assignments">
                                        <input type="hidden" name="format" value="pdf">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-file-pdf"></i> Generate PDF
                                        </button>
                                    </form>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Employee</th>
                                                    <th>Project</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentAssignments as $assignment)
                                                    <tr>
                                                        <td>{{ $assignment->user ? $assignment->user->name : 'N/A' }}</td>
                                                        <td>{{ $assignment->project ? $assignment->project->name : 'N/A' }}</td>
                                                        <td>
                                                            <span class="badge badge-{{
                                                                $assignment->assignment_status == 'Active' ? 'success' :
                                                                ($assignment->assignment_status == 'Pending HR Approval' ? 'warning' : 'secondary')
                                                            }}">
                                                                {{ $assignment->assignment_status }}
                                                            </span>
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

            @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
            <script>
                // Skill Distribution Chart
                document.addEventListener('DOMContentLoaded', function() {
                    var ctx = document.getElementById('skillChart').getContext('2d');
                    var skillChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: @json($skillDistribution->pluck('skill_name')),
                            datasets: [{
                                label: 'Number of Employees',
                                data: @json($skillDistribution->pluck('count')),
                                backgroundColor: '#4e73df',
                                hoverBackgroundColor: '#2e59d9',
                                borderColor: '#4e73df',
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            },
                            animation: {
                                duration: 2000,
                                easing: 'easeOutQuart'
                            }
                        }
                    });

                    // Project Modal Handler (Keep this if you still want the modal for details)
                    $('.project-item').on('click', function() {
                        var projectName = $(this).data('project-name');
                        var projectObjective = $(this).data('project-objective');
                        var projectCost = $(this).data('project-cost');
                        var projectStart = $(this).data('project-start');
                        var projectEnd = $(this).data('project-end');
                        var projectStatus = $(this).data('project-status');

                        $('#projectModalLabel').text(projectName);
                        $('#project-name').text(projectName);
                        $('#project-objective').text(projectObjective);
                        $('#project-cost').text('$' + projectCost);
                        $('#project-dates').text(projectStart + ' - ' + projectEnd);

                        var statusBadge = $('#project-status');
                        statusBadge.removeClass('badge-success badge-warning badge-danger badge-secondary');

                        if (projectStatus === 'Active') {
                            statusBadge.addClass('badge-success').text(projectStatus);
                        } else if (projectStatus === 'Planning') {
                            statusBadge.addClass('badge-warning').text(projectStatus);
                        } else if (projectStatus === 'Completed') {
                            statusBadge.addClass('badge-primary').text(projectStatus);
                        } else {
                            statusBadge.addClass('badge-secondary').text(projectStatus);
                        }
                    });
                });
            </script>

            @endpush

            </div>

        </div>
    </div>

<style>
    /* Custom animations */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out, opacity 0.3s ease;
        transform: translate(0, -50px);
        opacity: 0;
    }

    .modal.show .modal-dialog {
        transform: translate(0, 0);
        opacity: 1;
    }

    .breadcrumbs-container {
        animation: fadeInDown 0.5s ease;
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .list-group-item {
        transition: all 0.3s ease;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection
