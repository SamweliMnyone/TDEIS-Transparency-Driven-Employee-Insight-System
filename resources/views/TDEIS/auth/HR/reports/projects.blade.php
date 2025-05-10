@extends('TDEIS.auth.HR.reports.layouts.report_layout')

@section('title', $title)

@section('content')
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ public_path('LOGO_3.png') }}" class="rounded-circle img-fluid" style="max-height: 80px;">
    <h1 style="margin-top: 10px; font-size: 24px;">TRANSPARENCE DRIVEN EMPLOYEE ENSIGHT SYSTEM</h1>
</div>

<h1 style="text-align: center;">{{ $title }}</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Objective</th>
                <th>Manager</th>
                <th>Start Date</th>

            </tr>
        </thead>
        <tbody>
            @if(count($data['projects']) > 0)
                @foreach($data['projects'] as $project)
                    <tr>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->objective }}</td>
                        <td>{{ $project->manager ? $project->manager->name : 'N/A' }}</td>
                        <td>{{ $project->created_at ? $project->created_at->format('Y-m-d') : 'N/A' }}</td>

                    </tr>
                @endforeach
            @else
                <tr><td colspan="5">No projects found.</td></tr>
            @endif
        </tbody>
    </table>
    @endsection
