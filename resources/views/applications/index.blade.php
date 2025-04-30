@extends('layouts.app')

@section('title', 'Applications')

@section('content')
<div class="container">
    <h2 class="mb-4">Applications</h2>

    <!-- Filter Form -->
    <form action="{{ route('applications.index') }}" method="GET" class="mb-4">
        <div class="row g-3">
            <!-- Years of Experience Filter -->
            <div class="col-12 col-md-4">
                <label for="years_of_experience" class="form-label">Years of Experience</label>
                <select class="form-control" id="years_of_experience" name="years_of_experience" onchange="this.form.submit()">
                    <option value="">All</option>
                    @for ($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ request('years_of_experience') == $i ? 'selected' : '' }}>{{ $i }} Year{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                    <option value="10" {{ request('years_of_experience') == '10' ? 'selected' : '' }}>10+ Years</option>
                </select>
            </div>

            <!-- Position Applied For Filter -->
            <div class="col-12 col-md-4">
                <label for="position_applied_for" class="form-label">Position</label>
                <select class="form-control" id="position_applied_for" name="position_applied_for" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="Crusher Operator" {{ request('position_applied_for') == 'Crusher Operator' ? 'selected' : '' }}>Crusher Operator</option>
                    <option value="Milling and Flotation Operator" {{ request('position_applied_for') == 'Milling and Flotation Operator' ? 'selected' : '' }}>Milling and Flotation Operator</option>
                    <option value="Metallurgist" {{ request('position_applied_for') == 'Metallurgist' ? 'selected' : '' }}>Metallurgist</option>
                    <option value="Leaching, Solvent Extraction and Electrowinning Operator/Attendee" {{ request('position_applied_for') == 'Leaching, Solvent Extraction and Electrowinning Operator/Attendee' ? 'selected' : '' }}>Leaching, Solvent Extraction and Electrowinning Operator/Attendee</option>
                    <option value="Leaching, Solvent Extraction and Electrowinning Incharge" {{ request('position_applied_for') == 'Leaching, Solvent Extraction and Electrowinning Incharge' ? 'selected' : '' }}>Leaching, Solvent Extraction and Electrowinning Incharge</option>
                    <option value="Plastician/Plastic Welder" {{ request('position_applied_for') == 'Plastician/Plastic Welder' ? 'selected' : '' }}>Plastician/Plastic Welder</option>
                    <option value="Mechanical Fitter" {{ request('position_applied_for') == 'Mechanical Fitter' ? 'selected' : '' }}>Mechanical Fitter</option>
                    <option value="Coded Welder" {{ request('position_applied_for') == 'Coded Welder' ? 'selected' : '' }}>Coded Welder</option>
                    <option value="Electrician" {{ request('position_applied_for') == 'Electrician' ? 'selected' : '' }}>Electrician</option>
                    <option value="HR Assistant Officer" {{ request('position_applied_for') == 'HR Assistant Officer' ? 'selected' : '' }}>HR Assistant Officer</option>
                    <option value="Safety Officer" {{ request('position_applied_for') == 'Safety Officer' ? 'selected' : '' }}>Safety Officer</option>
                    <option value="Chemist" {{ request('position_applied_for') == 'Chemist' ? 'selected' : '' }}>Chemist</option>
                    <option value="Chemist" {{ request('position_applied_for') == 'Chemist' ? 'selected' : '' }}>Chemist</option>
                    <option value="Accounts Assistant" {{ request('position_applied_for') == 'Accounts Assistant' ? 'selected' : '' }}>Accounts Assistant</option>
                    <option value="Senior Accountant" {{ request('position_applied_for') == 'Senior Accountant' ? 'selected' : '' }}>Senior Accountant</option>
                    <option value="Stores Assistant" {{ request('position_applied_for') == 'Stores Assistant' ? 'selected' : '' }}>Stores Assistant</option>
                </select>
            </div>
        </div>
    </form>

    <!-- Applications Table -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    {{-- <th>Application ID</th> --}}
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Position</th>
                    <th>Years of Experience</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                    <tr>
                        {{-- <td>{{ $application->application_id }}</td> --}}
                        <td>{{ $application->first_name }} {{ $application->last_name }}</td>
                        <td>{{ $application->email }}</td>
                        <td>{{ $application->phone }}</td>
                        <td>{{ $application->position_applied_for }}</td>
                        <td>{{ $application->years_of_experience }} Years</td>
                        <td>
                            <span
                                class="badge
                                @if($application->status == 'Pending') bg-warning text-dark
                                @elseif($application->status == 'Accepted') bg-success
                                @elseif($application->status == 'Rejected') bg-danger
                                @else bg-secondary @endif">
                                {{ $application->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('applications.show', $application->id) }}" class="btn btn-info btn-sm mb-1">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
