@extends('TDEIS.auth.PM.body.app')

@section('yes')
    <title>TDEIS | Employee Dashboard</title>
    <style>
        .profile-img-container {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #f0f0f0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.3s ease;
        }

        .profile-img:hover {
            transform: scale(1.05);
        }

        .social-links a {
            color: #6c757d;
            font-size: 1.2rem;
            margin: 0 5px;
            transition: color 0.3s ease;
        }

        .social-links a:hover {
            color: #2e59d9;
        }

        .location {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .profile-img-container {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #f0f0f0;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .skills-section li {
            padding: 3px 0;
        }

        .social-links a {
            color: #6c757d;
            font-size: 1.2rem;
            margin: 0 5px;
        }
    </style>
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
            <div class="row">


                @isset($user)
                    <div class="row">
                        @foreach($employees->filter(fn($e) => $e->skills->count() > 0) as $employee)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">{{ $employee->name }}</h5>
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <div class="text-center mb-3">
                                                <img class="rounded-circle"
                                                    src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/user.png') }}"
                                                    alt="{{ $user->name }}" width="100" height="100" style="object-fit: cover;"
                                                    onerror="this.src='{{ asset('images/user.png') }}'">
                                            </div>

                                            <div class="text-center">
                                                <p class="mb-1"><i class="fa fa-envelope"></i> {{ $employee->email }}</p>
                                                <p class="mb-3"><i class="fa fa-phone"></i> {{ $employee->phone ?? 'Not provided' }}
                                                </p>
                                            </div>

                                            <button class="btn btn-outline-primary mt-auto" data-toggle="modal"
                                                data-target="#skillsModal{{ $employee->id }}">
                                                View Skills ({{ $employee->skills->count() }})
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Skills Modal -->
                                <div class="modal fade" id="skillsModal{{ $employee->id }}" tabindex="-1" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ $employee->name }}'s Skills</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @foreach($employee->skills as $skill)
                                                                        <div class="mb-3">
                                                                            <div class="d-flex justify-content-between mb-1">
                                                                                <strong>{{ $skill->skill_name }}</strong>
                                                                                <span class="badge badge-info">{{ $skill->years_of_experience }} yrs</span>
                                                                            </div>
                                                                            @php
                                                                                $width = match ($skill->proficiency) {
                                                                                    'Beginner' => 25,
                                                                                    'Intermediate' => 50,
                                                                                    'Advanced' => 75,
                                                                                    'Expert' => 100,
                                                                                    default => 0
                                                                                };
                                                                            @endphp
                                                                            <div class="progress">
                                                                                <div class="progress-bar" role="progressbar" style="width: {{ $width }}%"
                                                                                    aria-valuenow="{{ $width }}" aria-valuemin="0" aria-valuemax="100">
                                                                                    {{ $skill->proficiency }} ({{ $width }}%)
                                                                                </div>
                                                                            </div>
                                                                            @if($skill->description)
                                                                                <p class="mt-1 mb-0 small text-muted">{{ $skill->description }}</p>
                                                                            @endif
                                                                        </div>
                                                @endforeach
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                    </div>
                @endisset


            </div>
        </div>
    </div>
@endsection
