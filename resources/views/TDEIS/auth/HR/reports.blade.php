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
                <h1 class="h3 mb-0">HR Reports</h1>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Generate Reports</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('hr.report.generate') }}" method="GET">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="report_type">Report Type</label>
                            <select name="report_type" id="report_type" class="form-control" required>
                                <option value="">Select Report Type</option>
                                <option value="employees">Employee Skills Report</option>
                                <option value="projects">Projects Report</option>
                                <option value="assignments">Assignments Report</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="format">Output Format</label>
                            <select name="format" id="format" class="form-control">
                                <option value="view">View in Browser</option>
                                <option value="pdf">Download PDF</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                </form>
            </div>
        </div>
    </div>












</div></div>

@endsection
