@extends('layouts.app')

@section('title', 'Employees')

@section('content')
<style>
    .btn-xs {
        padding: 0.125rem 0.25rem;
        font-size: 0.75rem;
        line-height: 1.2;
    }
</style>
<div class="container">
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session()->has('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('employees.create') }}" class="btn btn-success btn-sm w-30 w-md-auto" style="background-color:#510404; color: #fff;"><i class="fas fa-user-plus"></i> Add New Employee</a>
    </div>

    <form action="{{ route('employees.index') }}" method="GET" class="mb-3">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by Name, Employee ID or Designation" value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100 mt-2 mt-md-0 mb-2 mb-md-0">Search</button>
            </div>
            <div class="col-6 col-md-2">
                <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm w-100 mt-2 mt-md-0 mb-2 mb-md-0">Reset</a>
            </div>
        </div>
    </form>

    @if($employees->isEmpty())
    <p class="text-center">No employees available.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Employee ID</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $employee->employee_full_name }}</td>
                            <td>{{ $employee->employee_id }}</td>
                            <td>{{ $employee->designation }}</td>
                            <td>{{ $employee->department }}</td>
                            <td>
                                @if ($employee->status == 'Active')
                                    <span class="badge bg-success">{{ $employee->status }}</span>
                                @elseif ($employee->status == 'Inactive')
                                    <span class="badge bg-secondary">{{ $employee->status }}</span>
                                @elseif ($employee->status == 'On Leave')
                                    <span class="badge bg-warning text-dark">{{ $employee->status }}</span>
                                @elseif ($employee->status == 'Terminated')
                                    <span class="badge bg-danger">{{ $employee->status }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $employee->status ?? 'N/A' }}</span>
                                @endif
                            </td>
                            <td class="d-flex gap-1 align-items-center">
                                <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-info btn-xs"><i class="fas fa-eye"></i> View</a>
                                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-xs ml-1"><i class="fas fa-edit"></i> Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection