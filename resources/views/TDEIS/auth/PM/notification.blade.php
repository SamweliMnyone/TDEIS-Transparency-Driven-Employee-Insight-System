@extends('TDEIS.auth.PM.body.app')

@section('yes')
    <title>TDEIS | Project Assignments</title>

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
                                <li class="breadcrumb-item active">Notifications</li>
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
                            <strong class="card-title">Employee Assignments</strong>
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
                                            <th>Proficiency Level</th>
                                            <th>Years Needed</th>
                                            <th>Status</th>
                                            <th>Assigned On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($assignments as $index => $assignment)
                                            <tr>
                                                <td>{{ $index + $assignments->firstItem() }}</td>
                                                <td>{{ $assignment->project->name ?? 'N/A' }}</td>
                                                <td>{{ $assignment->employee->name ?? 'N/A' }}</td>
                                                <td>{{ $assignment->requiredSkill->skill_name ?? 'N/A' }}</td>
                                                <td>{{ $assignment->proficiency_level ?? 'Not Specified' }}</td>
                                                <td>{{ $assignment->years_of_experience_needed ?? 'Not Specified' }}</td>
                                                <td>
                                                    <span class="badge badge-{{ strtolower($assignment->assignment_status) }}">
                                                        {{ $assignment->assignment_status }}
                                                    </span>
                                                </td>
                                                <td>{{ $assignment->created_at->format('Y-m-d H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No notifications available</td>
                                            </tr>
                                        @endforelse
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
                <form action="{{ route('pm.assignments.update', $assignment->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Assignment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="assignment_status" class="form-control">
                                <option value="Pending HR Approval" {{ $assignment->assignment_status == 'Pending HR Approval' ? 'selected' : '' }}>Pending HR Approval</option>
                                <option value="Approved" {{ $assignment->assignment_status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="Rejected" {{ $assignment->assignment_status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Years of Experience Needed</label>
                            <input type="number" name="years_of_experience_needed" class="form-control" value="{{ $assignment->years_of_experience_needed }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('.datatable').DataTable({
                "pageLength": 25,
                "responsive": true,
                "paging": false, // Disable DataTables pagination since we're using Laravel pagination
                "info": false,
                "searching": true
            });

            // SweetAlert for success/error messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            // Delete confirmation
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
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
                        this.submit();
                    }
                });
            });
        });
    </script>
    @endpush
@endsection
