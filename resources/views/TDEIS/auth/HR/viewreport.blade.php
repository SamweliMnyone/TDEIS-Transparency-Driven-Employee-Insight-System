@extends('TDEIS.auth.HR.body.app')

@section('yes')
    <title>TDEIS | Reports</title>

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
                                <li class="breadcrumb-item"><a href="{{ route('hr.dashboard') }}">Dashboard</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1 class="h3 mb-0">{{ $title }}</h1>
                <p class="text-muted">Generated on {{ now()->format('F j, Y') }}</p>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Email</th>
                                <th>Skills Count</th>
                                <th>Top Skills</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $employee)
                            <tr>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->skills_count }}</td>
                                <td>
                                    @foreach($employee->skills->take(3) as $skill)
                                    <span class="badge badge-primary">{{ $skill->skill_name }} ({{ $skill->proficiency }})</span>
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>





</div></div>

@endsection
