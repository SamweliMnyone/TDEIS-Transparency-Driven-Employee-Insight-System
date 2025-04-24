@extends('TDEIS.auth.employee.body.app')

@section('content')
    <title>TDEIS | Employee Profile</title>

    <style>
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .user-header .media-body {
                text-align: center;
            }

            .user-header img {
                margin-bottom: 15px;
            }

            .list-group-item {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .list-group-item span:last-child {
                margin-top: 5px;
                width: 100%;
                text-align: right;
            }
        }

        /* Error message styling */
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 80%;
            color: #dc3545;
        }

        .is-invalid {
            border-color: #dc3545;
        }

        /* Profile photo preview */
        #photo-preview {
            margin-top: 10px;
            width: 90px;
            /* Fixed width */
            height: 90px;
            /* Fixed height */
            overflow: hidden;
            /* Hide overflow */
            border-radius: 50%;
            border: 3px solid #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Image stays within its container */
        #preview-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            display: block;
        }
    </style>

    <!-- Include SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <div class="col-md-12">
        <aside class="profile-nav alt">
            <section class="card">
                <!-- Card Header with User Info -->
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

                <!-- User Details List -->
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fa fa-user mr-2"></i>
                            <strong>Full Name:</strong>
                        </span>
                        <span>{{ $user->name ?? 'Not specified' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fa fa-envelope-o mr-2"></i>
                            <strong>Email:</strong>
                        </span>
                        <span class="text-break">{{ $user->email ?? 'Not specified' }}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fa fa-phone mr-2"></i>
                            <strong>Phone:</strong>
                        </span>
                        <span class="text-break">{{ $user->phone ?? 'Not specified' }}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fa fa-venus-mars mr-2"></i>
                            <strong>Gender:</strong>
                        </span>
                        <span>
                            @if($user->gender)
                                {{ ucfirst($user->gender) }}
                            @else
                                Not specified
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fa fa-briefcase mr-2"></i>
                            <strong>Position:</strong>
                        </span>
                        <span>
                            @if($user->role)
                                {{ $user->role }}
                            @else
                                Not specified
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fa fa-calendar-plus-o mr-2"></i>
                            <strong>Registered:</strong>
                        </span>
                        <span>
                            {{ $user->created_at->format('M d, Y') ?? 'Not available' }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fa fa-birthday-cake mr-2"></i>
                            <strong>Date of Birth:</strong>
                        </span>
                        <span>
                            @if($user->date_of_birth)
                                {{ \Carbon\Carbon::parse($user->date_of_birth)->format('M d, Y') }}
                            @else
                                Not specified
                            @endif
                        </span>
                    </li>
                </ul>
            </section>
        </aside>
    </div>

    <div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h4 class="por-title mb-4">Skills & Expertise</h4>
            
            @foreach(auth()->user()->skills as $skill)
                @php
                    // Calculate percentage and color based on proficiency
                    [$percentage, $color] = match($skill->proficiency) {
                        'Beginner' => [25, '#FF6384'],    // Red
                        'Intermediate' => [50, '#36A2EB'], // Blue
                        'Advanced' => [75, '#FFCE56'],     // Yellow
                        'Expert' => [100, '#4BC0C0'],      // Teal
                        default => [0, '#CCCCCC']          // Grey
                    };
                    
                    // Simplified proficiency labels
                    $simpleProficiency = match($skill->proficiency) {
                        'Beginner' => 'Basic',
                        'Intermediate' => 'Intermediate',
                        'Advanced' => 'Advanced',
                        'Expert' => 'Expert',
                        default => 'New'
                    };
                @endphp
                
                <div class="skill-container mb-3">
                    <div class="skill-item">
                        <div class="skill-main d-flex align-items-center">
                            <div class="skill-name flex-grow-1">
                                <h5 class="mb-0">{{ $skill->skill_name }}</h5>
                            </div>
                            <div class="skill-level-box me-3">
                                <span class="proficiency-label">{{ $simpleProficiency }}</span>
                            </div>
                            <div class="skill-percentage">
                                <span>{{ $percentage }}%</span>
                            </div>
                        </div>
                        
                        <div class="progress mt-2">
                            <div class="progress-bar" 
                                 role="progressbar" 
                                 style="width: {{ $percentage }}%; background-color: {{ $color }}"
                                 aria-valuenow="{{ $percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        
                        <div class="skill-hover-details">
                            <div class="detail-row">
                                <span class="detail-label">Skill Level:</span>
                                <span class="detail-value">{{ $skill->proficiency }} ({{ $percentage }}%)</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Experience:</span>
                                <span class="detail-value">{{ $skill->years_of_experience ?? '0' }} years</span>
                            </div>
                            @if($skill->description)
                            <div class="detail-row">
                                <span class="detail-label">Details:</span>
                                <span class="detail-value">{{ $skill->description }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            
        </div>
    </div>
</div>

<style>
    .skill-container {
        position: relative;
    }
    
    .skill-item {
        padding: 12px 15px;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
    }
    
    .skill-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .skill-main {
        height: 32px; /* Fixed height for perfect alignment */
    }
    
    .skill-name h5 {
        font-weight: 600;
        color: #2e59d9;
        line-height: 1.2;
    }
    
    .skill-level-box {
        background-color: rgba(0, 0, 0, 0.08);
        padding: 4px 10px;
        border-radius: 4px;
        min-width: 90px;
        text-align: center;
    }
    
    .proficiency-label {
        font-weight: 700;
        color: #000;
        font-size: 0.85rem;
        text-transform: uppercase;
    }
    
    .skill-percentage {
        font-weight: 700;
        color: #2e59d9;
        min-width: 40px;
        text-align: right;
    }
    
    .progress {
        height: 8px;
        background-color: #f0f0f0;
        border-radius: 4px;
    }
    
    .progress-bar {
        border-radius: 4px;
        transition: width 0.6s ease;
    }
    
    .skill-hover-details {
        display: none;
        padding-top: 12px;
        margin-top: 12px;
        border-top: 1px dashed #e3e6f0;
    }
    
    .skill-item:hover .skill-hover-details {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }
    
    .detail-label {
        font-weight: 600;
        color: #5a5c69;
    }
    
    .detail-value {
        color: #6c757d;
        text-align: right;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">Update Profile</div>
            <div class="card-body card-block">
                <form action="{{ route('employee.profile.update') }}" method="post" class="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Profile Photo</div>
                            <div class="form-control" style="padding: 5px; display: flex; align-items: center;">
                                <div style="position: relative; width: 100%;">
                                    <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                                        style="opacity: 0; position: absolute; width: 100%; height: 100%; cursor: pointer;">
                                    <div
                                        style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                        <span id="photo-filename" style="color: #888;">No file chosen</span>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            style="padding: 2px 8px; font-size: 12px;"
                                            onclick="document.getElementById('profile_photo').click()">
                                            Choose File
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group-addon"><i class="fa fa-camera"></i></div>
                        </div>
                        @error('profile_photo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="mt-2" id="photo-preview"
                            style="display: {{ $user->profile_picture ? 'block' : 'none' }};">
                            <img id="preview-image"
                                src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : '#' }}"
                                alt="Preview"
                                style="max-width: 150px; max-height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid #f0f0f0;">
                            <button type="button" class="btn btn-sm btn-outline-danger ml-2" onclick="clearPhoto()"
                                style="padding: 2px 8px; font-size: 12px;">
                                Remove
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Full Name</div>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}">
                            <div class="input-group-addon"><i class="fa fa-user"></i></div>
                        </div>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Email</div>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}">
                            <div class="input-group-addon"><i class="fa fa-envelope"></i></div>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Date of Birth</div>
                            <input type="date" id="date_of_birth" name="date_of_birth"
                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}">

                            <div class="input-group-addon"><i class="fa fa-birthday-cake"></i></div>
                        </div>
                        @error('date_of_birth')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Gender</div>
                            <select id="gender" name="gender" class="form-control @error('gender') is-invalid @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male
                                </option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female
                                </option>
                            </select>
                            <div class="input-group-addon"><i class="fa fa-venus-mars"></i></div>
                        </div>
                        @error('gender')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Phone</div>
                            <input type="tel" id="phone" name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $user->phone) }}" placeholder="Enter phone number" required>
                            <div class="input-group-addon"><i class="fa fa-phone"></i></div>
                        </div>
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-actions form-group">
                        <button type="submit" class="btn btn-primary btn-sm">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">Update Password</div>
            <div class="card-body card-block">
                <form action="{{ route('employee.profile.update-password') }}" method="post" class="">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Current Password</div>
                            <input type="password" id="current_password" name="current_password"
                                class="form-control @error('current_password', 'passwordErrors') is-invalid @enderror"
                                placeholder="Enter current password" required>
                            <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                        </div>
                        @error('current_password', 'passwordErrors')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">New Password</div>
                            <input type="password" id="new_password" name="new_password"
                                class="form-control @error('new_password', 'passwordErrors') is-invalid @enderror"
                                placeholder="Enter new password" required>
                            <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                        </div>
                        @error('new_password', 'passwordErrors')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Confirm Password</div>
                            <input type="password" id="confirm_password" name="confirm_password"
                                class="form-control @error('confirm_password', 'passwordErrors') is-invalid @enderror"
                                placeholder="Confirm new password" required>
                            <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                        </div>
                        @error('confirm_password', 'passwordErrors')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-actions form-group">
                        <button type="submit" class="btn btn-primary btn-sm">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">Delete Account</div>
            <div class="card-body card-block">
                <form action="{{ route('employee.profile.delete-account') }}" method="post" class="" id="deleteAccountForm">
                    @csrf
                    @method('DELETE')

                    <div class="alert alert-danger">
                        Warning: Once you delete your account, all your data will be
                        permanently removed.
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">Confirm Password</div>
                            <input type="password" id="confirm_password" name="confirm_password"
                                class="form-control @error('confirm_password', 'deleteErrors') is-invalid @enderror"
                                placeholder="Enter your password to confirm" required>
                            <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                        </div>
                        @error('confirm_password', 'deleteErrors')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-actions form-group">
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete()">Delete
                            Account</button>
                    </div>
                </form>
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
                                            });

        // Profile photo handling
        document.getElementById('profile_photo').addEventListener('change', function (e) {
            const fileInput = e.target;
            const fileName = fileInput.files[0] ? fileInput.files[0].name : 'No file chosen';
            document.getElementById('photo-filename').textContent = fileName;

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('photo-preview').style.display = 'block';
                }
                reader.readAsDataURL(fileInput.files[0]);
            }
        });

        function clearPhoto() {
            document.getElementById('profile_photo').value = '';
            document.getElementById('photo-filename').textContent = 'No file chosen';
            document.getElementById('photo-preview').style.display = 'none';
            document.getElementById('preview-image').src = '#';
        }

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