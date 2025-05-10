@extends('TDEIS.auth.PM.body.app')

@section('yes')
    <title>TDEIS | Project Manager Profile</title>

    <div class="breadcrumbs">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-12">
                    <div class="page-header d-flex align-items-center justify-content-between">
                        <div class="page-title">
                            <h1 class="mb-0">Dashboard</h1>
                        </div>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('pm.dashboard') }}" >
                                    </i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item text-primary"">
                               Profile
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="animated fadeIn">
            <div class="row">
                <!-- Profile Card Column -->
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header user-header alt bg-dark">
                            <div class="media d-flex flex-column flex-md-row align-items-center">
                                <a href="#" class="mb-3 mb-md-0 mr-md-3">
                                    <img class="align-self-center rounded-circle img-fluid"
                                        style="width:85px; height:85px; object-fit: cover;" alt="User profile"
                                        src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/user.png') }}">
                                </a>
                                <div class="media-body text-center text-md-left">
                                    <h2 class="text-light display-6 mb-1">{{ $user->name ?? 'Not specified' }}</h2>
                                    <p class="mb-0">{{ $user->email ?? 'Not specified' }}</p>
                                </div>
                            </div>
                        </div>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-user mr-2"></i><strong>Full Name:</strong></span>
                                <span>{{ $user->name ?? 'Not specified' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-envelope-o mr-2"></i><strong>Email:</strong></span>
                                <span class="text-break">{{ $user->email ?? 'Not specified' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-phone mr-2"></i><strong>Phone:</strong></span>
                                <span class="text-break">{{ $user->phone ?? 'Not specified' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-venus-mars mr-2"></i><strong>Gender:</strong></span>
                                <span>{{ $user->gender ? ucfirst($user->gender) : 'Not specified' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-briefcase mr-2"></i><strong>Position:</strong></span>
                                <span>{{ $user->role ?? 'Not specified' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-calendar-plus-o mr-2"></i><strong>Registered:</strong></span>
                                <span>{{ $user->created_at->format('M d, Y') ?? 'Not available' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-birthday-cake mr-2"></i><strong>Date of Birth:</strong></span>
                                <span>
                                    @if($user->date_of_birth)
                                        {{ \Carbon\Carbon::parse($user->date_of_birth)->format('M d, Y') }}
                                    @else
                                        Not specified
                                    @endif
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Main Content Column -->
                <div class="col-lg-12 col-md-12">
                    <!-- Update Profile Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <strong>Update Profile</strong>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pm.profile.update') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Profile Photo</label>
                                    <div class="col-md-9">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="profile_photo" name="profile_photo" accept="image/*">
                                            <label class="custom-file-label" for="profile_photo">Choose file</label>
                                        </div>
                                        @error('profile_photo')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div class="mt-2" id="photo-preview">
                                            <img id="preview-image"
                                                 src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : '#' }}"
                                                 alt="Preview" style="max-width: 100px; max-height: 100px; border-radius: 50%;">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Full Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               name="name" value="{{ old('name', $user->name) }}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               name="email" value="{{ old('email', $user->email) }}">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Date of Birth</label>
                                    <div class="col-md-9">
                                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                               name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}">
                                        @error('date_of_birth')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Gender</label>
                                    <div class="col-md-9">
                                        <select class="form-control @error('gender') is-invalid @enderror" name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('gender')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Phone</label>
                                    <div class="col-md-9">
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                               name="phone" value="{{ old('phone', $user->phone) }}" required>
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-9 offset-md-3">
                                        <button type="submit" class="btn btn-primary">Update Profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Update Password Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <strong>Update Password</strong>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pm.profile.update-password') }}" method="post">
                                @csrf
                                @method('PUT')

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Current Password</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control @error('current_password', 'passwordErrors') is-invalid @enderror"
                                               name="current_password" required>
                                        @error('current_password', 'passwordErrors')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">New Password</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control @error('new_password', 'passwordErrors') is-invalid @enderror"
                                               name="new_password" required>
                                        @error('new_password', 'passwordErrors')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Confirm Password</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control @error('confirm_password', 'passwordErrors') is-invalid @enderror"
                                               name="confirm_password" required>
                                        @error('confirm_password', 'passwordErrors')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-9 offset-md-3">
                                        <button type="submit" class="btn btn-primary">Update Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Delete Account Card -->
                    <div class="card">
                        <div class="card-header">
                            <strong>Delete Account</strong>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('pm.profile.delete-account') }}" method="post" id="deleteAccountForm">
                                @csrf
                                @method('DELETE')

                                <div class="alert alert-danger">
                                    <strong>Warning:</strong> Once you delete your account, all your data will be permanently removed.
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Confirm Password</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control @error('confirm_password', 'deleteErrors') is-invalid @enderror"
                                               name="confirm_password" required>
                                        @error('confirm_password', 'deleteErrors')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-9 offset-md-3">
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete Account</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Display SweetAlert notifications if they exist
        document.addEventListener('DOMContentLoaded', function () {
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

            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: '{{ session('warning') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            // Profile photo handling
            document.getElementById('profile_photo').addEventListener('change', function (e) {
                const fileInput = e.target;
                const fileName = fileInput.files[0] ? fileInput.files[0].name : 'No file chosen';
                const label = fileInput.nextElementSibling;
                label.textContent = fileName;

                if (fileInput.files && fileInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const preview = document.getElementById('preview-image');
                        preview.src = e.target.result;
                        document.getElementById('photo-preview').style.display = 'block';
                    }
                    reader.readAsDataURL(fileInput.files[0]);
                }
            });
        });

        // Delete account confirmation
        function confirmDelete() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this! All your data will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteAccountForm').submit();
                }
            });
        }
    </script>
@endsection
