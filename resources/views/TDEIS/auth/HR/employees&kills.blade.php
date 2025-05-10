@extends('TDEIS.auth.HR.body.app')

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

        .skills-section li {
            padding: 3px 0;
        }

        /* New styles for top experts notification */
        .alert-success {
            background-color: #f8fff8;
            border-color: #d1e7dd;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .alert-heading i {
            color: #2e59d9;
        }

        .top-expert-img {
            transition: transform 0.3s ease;
            width: 60px;
            height: 60px;
            object-fit: cover;
            border: 2px solid #e9ecef;
        }

        .top-expert-img:hover {
            transform: scale(1.1);
            border-color: #2e59d9;
        }

        .expert-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #ffc107;
            color: #212529;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .expert-item {
            position: relative;
            padding: 10px;
            transition: all 0.3s ease;
        }

        .expert-item:hover {
            background-color: #f8f9fa;
            border-radius: 5px;
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
            <!-- Top Experts Notification Section -->
            @if($topExperts->count() > 0)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card border-success mb-4">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0"><i class="fa fa-trophy mr-2"></i>Top 5 Experts</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">These employees have demonstrated exceptional skills across multiple areas:</p>
                            <div class="row text-center">
                                @foreach($topExperts as $index => $expert)
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4 expert-item">
                                    <div class="position-relative d-inline-block mb-2">
                                        <img src="{{ $expert->profile_picture ? asset('storage/' . $expert->profile_picture) : asset('images/user.png') }}"
                                             class="top-expert-img rounded-circle"
                                             alt="{{ $expert->name }}"
                                             onerror="this.src='{{ asset('images/user.png') }}'">
                                        <span class="expert-badge">{{ $index + 1 }}</span>
                                    </div>
                                    <h6 class="mb-1">{{ $expert->name }}</h6>
                                    <small class="text-muted d-block">
                                        <i class="fa fa-star text-warning"></i>
                                        {{ $expert->skills->whereIn('proficiency', ['Advanced', 'Expert'])->count() }} expert skills
                                    </small>
                                    <small class="text-primary">
                                        @php
                                            $topSkill = $expert->skills->sortByDesc('years_of_experience')->first();
                                        @endphp
                                        @if($topSkill)
                                        Top: {{ $topSkill->skill_name }} ({{ $topSkill->years_of_experience }} yrs)
                                        @endif
                                    </small>
                                    <button class="btn btn-sm btn-outline-primary mt-2" data-toggle="modal" data-target="#expertModal{{ $expert->id }}">
                                        View Skills
                                    </button>
                                </div>

                                <!-- Expert Skills Modal -->
                                <div class="modal fade" id="expertModal{{ $expert->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title">
                                                    <i class="fa fa-trophy mr-2"></i>{{ $expert->name }}'s Expert Skills
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    @foreach($expert->skills->whereIn('proficiency', ['Advanced', 'Expert'])->sortByDesc('years_of_experience') as $skill)
                                                    <div class="col-md-6 mb-3">
                                                        <div class="card h-100">
                                                            <div class="card-body">
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <h6 class="mb-0 font-weight-bold">{{ $skill->skill_name }}</h6>
                                                                    <span class="badge badge-{{ $skill->proficiency == 'Expert' ? 'warning' : 'info' }}">
                                                                        {{ $skill->proficiency }}
                                                                    </span>
                                                                </div>
                                                                <div class="d-flex justify-content-between small text-muted mb-2">
                                                                    <span>{{ $skill->years_of_experience }} years experience</span>
                                                                    <span>{{ $skill->created_at->diffForHumans() }}</span>
                                                                </div>
                                                                @if($skill->description)
                                                                <p class="small mb-0">{{ $skill->description }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Main Employee Cards Section -->
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
                                                src="{{ $employee->profile_picture ? asset('storage/' . $employee->profile_picture) : asset('images/user.png') }}"
                                                alt="{{ $employee->name }}" width="100" height="100" style="object-fit: cover;"
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
