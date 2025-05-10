@extends('TDEIS.auth.HR.body.app')

@section('yes')
    <title>TDEIS | Employee Confimation</title>

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
                                <li class="breadcrumb-item active">Hiring</li>
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
                            <strong style="font-weight: bold" class="card-title">Confirmation of Employment</strong>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Project</th>
                                            <th>Employee</th>
                                            <th>Required Skill</th>
                                            <th>Status</th>
                                            <th>Assigned On</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assignments as $index => $assignment)
                                        <tr>
                                            <td>{{ $index + $assignments->firstItem() }}</td>
                                            <td>{{ $assignment->project->name ?? 'N/A' }}</td>
                                            <td>{{ $assignment->employee->name ?? 'N/A' }}</td>
                                            <td>{{ $assignment->requiredSkill->skill_name ?? 'N/A' }}</td>

                                            <td>
                                                <span class="badge badge-{{ strtolower($assignment->assignment_status) }}">
                                                    {{ $assignment->assignment_status }}
                                                </span>
                                            </td>
                                            <td>{{ $assignment->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <div class="d-flex align-items-center" style="gap: 8px;">
                                                    <button class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#editAssignmentModal{{ $assignment->id }}">
                                                        <i class="fa fa-edit"></i> Confirm
                                                    </button>

                                                    <form action="{{ route('hr.assignments.destroy', $assignment->id) }}" method="POST" style="display:inline" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>

                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Showing {{ $assignments->firstItem() }} to {{ $assignments->lastItem() }} of {{ $assignments->total() }}
                                    entries
                                </div>
                                <div>
                                    {!! $assignments->appends(['search' => request('search')])->onEachSide(1)->links('pagination::bootstrap-4') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($assignments as $assignment)
    <!-- Edit Assignment Modal -->
    <div class="modal fade" id="editAssignmentModal{{ $assignment->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('hr.assignments.update', $assignment->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 style="font-weight: bold" class="modal-title">Confirmation of Employment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="assignment_status" class="form-control">
                                <option value="Approved" {{ $assignment->assignment_status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="Rejected" {{ $assignment->assignment_status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

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
                confirmButtonText: 'Yes, delete it!',
                showConfirmButton: true // ✅ correct placement
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = this.closest('.delete-form');
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
