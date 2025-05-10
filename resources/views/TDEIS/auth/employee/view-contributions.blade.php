@extends('TDEIS.auth.employee.body.app')

@section('content')
    <title>TDEIS | Contribution</title>
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap CSS for modals -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Skills & Expertise</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">View Contribution</li>
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fa fa-list-alt"></i> View Contributions</h4>
                        </div>

                        <div class="card-body">
                            @if(session('success'))
                                <script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: '{{ session('success') }}',
                                        timer: 3000,
                                        showConfirmButton: false
                                    });
                                </script>
                            @endif

                            <div class="table-responsive">
                                <table class="table  table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Description</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($contributions as $contribution)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $contribution->title }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $contribution->type == 'certificate' ? 'success' : 'info' }}">
                                                        {{ ucfirst($contribution->type) }}
                                                    </span>
                                                </td>
                                                <td>{{ Str::limit($contribution->description, 50) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($contribution->date)->format('d M Y') }}</td>
                                                <td>

                                                    <!-- Edit Button with Modal Trigger -->
                                                    <button type="button" class="btn btn-sm btn-primary edit-btn" title="Edit"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $contribution->id }}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>

                                                    <!-- Delete Button with Modal Trigger -->
                                                    <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                        title="Delete" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal{{ $contribution->id }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editModal{{ $contribution->id }}" tabindex="-1"
                                                aria-labelledby="editModalLabel{{ $contribution->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel{{ $contribution->id }}">
                                                                Edit Contribution</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('contributions.update', $contribution->id) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="editTitle{{ $contribution->id }}"
                                                                        class="form-label">Title</label>
                                                                    <input type="text" class="form-control"
                                                                        id="editTitle{{ $contribution->id }}" name="title"
                                                                        value="{{ $contribution->title }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="editType{{ $contribution->id }}"
                                                                        class="form-label">Type</label>
                                                                    <select class="form-control"
                                                                        id="editType{{ $contribution->id }}" name="type"
                                                                        required>
                                                                        <option value="certificate" {{ $contribution->type == 'certificate' ? 'selected' : '' }}>Certificate</option>
                                                                        <option value="project" {{ $contribution->type == 'project' ? 'selected' : '' }}>
                                                                            Project</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="editDescription{{ $contribution->id }}"
                                                                        class="form-label">Description</label>
                                                                    <textarea class="form-control"
                                                                        id="editDescription{{ $contribution->id }}"
                                                                        name="description"
                                                                        rows="3">{{ $contribution->description }}</textarea>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="editDate{{ $contribution->id }}"
                                                                        class="form-label">Date</label>
                                                                    <input type="date" class="form-control"
                                                                        id="editDate{{ $contribution->id }}" name="date"
                                                                        value="{{ $contribution->date->format('Y-m-d') }}"
                                                                        required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="editFile{{ $contribution->id }}"
                                                                        class="form-label">File</label>
                                                                    <input type="file" class="form-control"
                                                                        id="editFile{{ $contribution->id }}" name="file">
                                                                    @if($contribution->file_path)
                                                                        <div class="mt-2">
                                                                            <small>Current file:
                                                                                <a href="{{ asset('storage/' . $contribution->file_path) }}"
                                                                                    target="_blank">View file</a>
                                                                            </small>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save
                                                                    changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>


                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No contributions found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                {{-- Pagination --}}
                                @if($contributions->hasPages())
                                    <div class="d-flex justify-content-center">
                                        {{ $contributions->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // SweetAlert for delete confirmation
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Display success message from session
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // Display error message from session
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>
@endsection
