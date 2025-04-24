@extends('TDEIS.auth.admin.body.app')

@section('yes')

<title>TDEIS | Manage Users</title>
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font Awesome for icons -->


    <!-- SweetAlert Notifications -->
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

    @if(Session::has('warning'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Warning!',
            text: '{{ Session::get('warning') }}',
            showConfirmButton: true
        });
    </script>
    @endif

    @if(Session::has('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ Session::get('error') }}',
            showConfirmButton: true
        });
    </script>
    @endif

    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Manage {{ ucfirst($userType) }}s</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Manage {{ ucfirst($userType) }}s</li>
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
                            <strong class="card-title">{{ ucfirst($userType) }} Table</strong>
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Add User Button -->
                                <div style="margin:10px">
                                    <button class="btn btn-success" data-toggle="modal" data-target="#addUserModal">
                                        <i class="fa fa-plus"></i> Add {{ ucfirst($userType) }}
                                    </button>
                                </div>
                                
                                <!-- Search Input -->
                                <div class="input-group" style="width: auto;">
                                    <input type="text" id="liveSearchInput" class="form-control" 
                                        placeholder="Search {{ $userType }}s..." 
                                        autocomplete="off">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="fa fa-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Profile</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>DOB</th>
                                        <th>Gender</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>{{ $users->firstItem() + $loop->index }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if ($user->profile_picture)
                                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" width="50"
                                                        height="50" style="object-fit:cover; border-radius:50%;">
                                                @else
                                                    <div style="width:50px; height:50px; border-radius:50%; background:#eee; display:flex; align-items:center; justify-content:center;">
                                                        <i class="fa fa-user text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
  
                                            <td>{{ $user->phone ?? '-' }}</td>
                                            <td>{{ $user->address ?? '-' }}</td>
                                            <td>
                                                {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td>{{ $user->gender ?? '-' }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <!-- Edit Button -->
                                                    <button class="btn btn-sm" data-toggle="modal"
                                                        data-target="#editModal{{ $user->id }}" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </button>

                                                    <!-- Delete Button -->
                                                    <form action="{{ route('user.delete', ['id' => $user->id, 'type' => $userType]) }}" method="POST"
                                                        class="delete-form d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                                                        <button type="submit" class="btn btn-sm" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>

                                                <!-- Edit Modal -->
                                                <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1"
                                                    role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <form method="POST"
                                                                action="{{ route('user.update', $user->id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Edit {{ ucfirst($userType) }}</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal">
                                                                        <span>&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    @if($errors->any())
                                                                        <div class="alert alert-danger">
                                                                            <ul class="mb-0">
                                                                                @foreach($errors->all() as $error)
                                                                                    <li>{{ $error }}</li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                    @endif
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Name <span class="text-danger">*</span></label>
                                                                                <input type="text" name="name"
                                                                                    class="form-control @error('name') is-invalid @enderror"
                                                                                    value="{{ old('name', $user->name) }}"
                                                                                    required>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Email <span class="text-danger">*</span></label>
                                                                                <input type="email" name="email"
                                                                                    class="form-control @error('email') is-invalid @enderror"
                                                                                    value="{{ old('email', $user->email) }}"
                                                                                    required>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Phone</label>
                                                                                <input type="text" name="phone"
                                                                                    class="form-control @error('phone') is-invalid @enderror"
                                                                                    value="{{ old('phone', $user->phone) }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>Address</label>
                                                                                <input type="text" name="address"
                                                                                    class="form-control @error('address') is-invalid @enderror"
                                                                                    value="{{ old('address', $user->address) }}">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Date of Birth</label>
                                                                                <input type="date" name="date_of_birth"
                                                                                    class="form-control @error('date_of_birth') is-invalid @enderror"
                                                                                    value="{{ old('date_of_birth', $user->date_of_birth) }}">
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label>Gender</label>
                                                                                <select name="gender"
                                                                                    class="form-control @error('gender') is-invalid @enderror">
                                                                                    <option value="">Select Gender</option>
                                                                                    <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                                                    <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Cancel</button>
                                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">No {{ $userType }}s found</h5>
                                                    @if(request('search'))
                                                        <p class="text-muted">Your search for "{{ request('search') }}" did not
                                                            match any {{ $userType }}s</p>
                                                        <a href="{{ route('manage.users', ['type' => $userType]) }}"
                                                            class="btn btn-sm btn-outline-primary mt-2">
                                                            Clear search
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }}
                                    entries
                                </div>
                                <div>
                                    {!! $users->appends(['search' => request('search')])->onEachSide(1)->links('pagination::bootstrap-4') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('user.create') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="role" value="{{ $userType }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New {{ ucfirst($userType) }}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select name="gender" class="form-control">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="address" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add {{ ucfirst($userType) }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

        // Live search with AJAX
        document.getElementById('liveSearchInput').addEventListener('input', function() {
            const searchTerm = this.value.trim();
            
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }
            
            this.searchTimeout = setTimeout(() => {
                if (searchTerm.length >= 1 || searchTerm.length === 0) {
                    fetchUsers(searchTerm);
                }
            }, 300);
        });

        function fetchUsers(searchTerm = '') {
            fetch(`{{ route('manage.users', ['type' => $userType]) }}?search=${encodeURIComponent(searchTerm)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTableBody = doc.querySelector('tbody');
                
                if (newTableBody) {
                    document.querySelector('tbody').innerHTML = newTableBody.innerHTML;
                }
                
                const newPagination = doc.querySelector('.pagination');
                if (newPagination) {
                    document.querySelector('.pagination').innerHTML = newPagination.innerHTML;
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endsection