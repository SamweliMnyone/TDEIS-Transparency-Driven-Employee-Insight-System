<!-- resources/views/employee/skills/index.blade.php -->
@extends('TDEIS.auth.employee.body.app')

@section('content')
<title>TDEIS | Skills & Expertise</title>
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                                <li class="breadcrumb-item active">Skills & Expertise</li>
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
                            <strong class="card-title">My Skills</strong>
                            <button class="btn btn-success float-right" data-toggle="modal" data-target="#addSkillModal">
                                <i class="fa fa-plus"></i> Add Skill
                            </button>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Skill</th>
                                        <th>Proficiency</th>
                                        <th>Experience</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($skills as $skill)
                                        <tr>
                                            <td>{{ $skill->skill_name }}</td>
                                            <td>{{ $skill->proficiency }}</td>
                                            <td>{{ $skill->years_of_experience ? $skill->years_of_experience.' years' : '-' }}</td>
                                            <td>{{ $skill->description ?? '-' }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" data-toggle="modal" 
                                                    data-target="#editSkillModal{{ $skill->id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <form action="{{ route('employee.skills.destroy', $skill->id) }}" method="POST" 
                                                    class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">No skills added yet</h5>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $skills->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Skill Modal -->
    <div class="modal fade" id="addSkillModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('employee.skills.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Skill</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Skill Name <span class="text-danger">*</span></label>
                            <input type="text" name="skill_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Proficiency Level <span class="text-danger">*</span></label>
                            <select name="proficiency" class="form-control" required>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                                <option value="Expert">Expert</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Years of Experience</label>
                            <input type="number" name="years_of_experience" class="form-control" min="0">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Skill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($skills as $skill)
    <!-- Edit Skill Modal -->
    <div class="modal fade" id="editSkillModal{{ $skill->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('employee.skills.update', $skill->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Skill</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Skill Name <span class="text-danger">*</span></label>
                            <input type="text" name="skill_name" class="form-control" 
                                value="{{ $skill->skill_name }}" required>
                        </div>
                        <div class="form-group">
                            <label>Proficiency Level <span class="text-danger">*</span></label>
                            <select name="proficiency" class="form-control" required>
                                <option value="Beginner" {{ $skill->proficiency == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="Intermediate" {{ $skill->proficiency == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="Advanced" {{ $skill->proficiency == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                                <option value="Expert" {{ $skill->proficiency == 'Expert' ? 'selected' : '' }}>Expert</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Years of Experience</label>
                            <input type="number" name="years_of_experience" class="form-control" 
                                value="{{ $skill->years_of_experience }}" min="0">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ $skill->description }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Skill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    @if(Session::has('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ Session::get('success') }}',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
    @endif

    <script>
        // Delete confirmation with SweetAlert
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
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
    </script>
@endsection