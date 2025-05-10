@extends('TDEIS.auth.HR.reports.layouts.report_layout')

@section('title', $title)

@section('content')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ public_path('LOGO_3.png') }}" class="rounded-circle img-fluid" style="max-height: 80px;">
    <h1 style="margin-top: 10px; font-size: 24px;">TRANSPARENCE DRIVEN EMPLOYEE ENSIGHT SYSTEM</h1>
</div>

<h1 style="text-align: center;">{{ $title }}</h1>

@if($startDate && $endDate)
    <p style="text-align: center;">Date Range: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
@endif

<table border="1" cellpadding="5" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>Employee</th>
            <th>Project</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data['assignments'] as $assignment)
            <tr>
                <td>{{ $assignment->employee ? $assignment->employee->name : 'N/A' }}</td>
                <td>{{ $assignment->project ? $assignment->project->name : 'N/A' }}</td>
                <td>{{ $assignment->assignment_status }}</td>
                <td>{{ $assignment->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="text-align: center;">No assignments found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
