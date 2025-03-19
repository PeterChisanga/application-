@extends('layouts.app')

@section('title', auth()->user()->role === 'admin' ? 'Admin Dashboard' : ucfirst(auth()->user()->role) . ' Dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card text-white" style="background-color:#510404;">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-users"></i> Employees
                    </h5>
                    <p class="card-text h4">{{ $employeesTotal }}</p>
                    <a href="{{ route('employees.index') }}" class="btn btn-light btn-sm">View Details</a>
                </div>
            </div>
        </div>

        @if(in_array(auth()->user()->role, ['hr', 'admin']))
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-file-alt"></i> Job Applications
                        </h5>
                        <p class="card-text h4">{{ $applicationsTotal }}</p>
                        <a href="{{ route('applications.index') }}" class="btn btn-light btn-sm">View Details</a>
                    </div>
                </div>
            </div>
        @endif

        @if(auth()->user()->role === 'admin')
            <div class="col-md-3 mb-3">
                <div class="card text-white" style="background-color:brown;">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-truck"></i> Equipment
                        </h5>
                        <p class="card-text h4">{{ $equipmentsTotal }}</p>
                        <a href="{{ route('equipments.index') }}" class="btn btn-light btn-sm">View Details</a>
                    </div>
                </div>
            </div>
        @endif

        @if(auth()->user()->role === 'admin')
            <div class="col-md-3 mb-3">
                <div class="card text-white bg-secondary">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-warehouse"></i> Warehouse/Inventory
                        </h5>
                        <p class="card-text h4">1345</p>
                        <a href="{{ route('not-implemented-yet') }}" class="btn btn-light btn-sm">View Details</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection