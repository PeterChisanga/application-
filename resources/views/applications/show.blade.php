@extends('layouts.app')

@section('title', 'Applicant Details')

@section('content')
<div class="container">
    <a href="{{ route('applications.index') }}" class="btn btn-secondary mb-4">Back to Applications</a>
    <a href="{{ route('applications.edit',$application->id)}}" class="btn btn-primary mb-4">Update Application Status</a>

    <h2 class="mb-4">Applicant Details</h2>
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">{{ $application->first_name }} {{ $application->last_name }}</h5>
            <hr>
            <p><strong>Application Status:</strong> {{ $application->status }}</p>
            <p><strong>Email:</strong> {{ $application->email }}</p>
            <p><strong>Phone:</strong> {{ $application->phone }}</p>
            <p><strong>NRC Number/ Passport Number:</strong> {{ $application->nrc_number }}</p>
            <p><strong>Position Applied For:</strong> {{ $application->position_applied_for }}</p>
            <p><strong>Years of Experience:</strong> {{ $application->years_of_experience }} Year{{ $application->years_of_experience > 1 ? 's' : '' }}</p>

            <div class="mb-3">
                <p><strong>CV :</strong>
                    @if ($application->resume_path)
                        <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" class="btn btn-link">View CV</a>
                    @else
                        <span class="text-muted">Not provided</span>
                    @endif
                </p>
            </div>

            <div class="mb-3">
                <p><strong>Cover Letter:</strong>
                    @if ($application->cover_letter_path)
                        <a href="{{ asset('storage/' . $application->cover_letter_path) }}" target="_blank" class="btn btn-link">View Cover Letter</a>
                    @else
                        <span class="text-muted">Not provided</span>
                    @endif
                </p>
            </div>

            <div>
                <p><strong>Certificates:</strong>
                @forelse ($application->certificates as $certificate)
                        <a href="{{ asset('storage/' . $certificate->file_path) }}" target="_blank" class="btn btn-link">View Certificates</a></p>
                @empty
                    <p class="text-muted">No certificates uploaded.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
