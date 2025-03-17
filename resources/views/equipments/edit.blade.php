@extends('layouts.app')

@section('title', 'Edit Equipment')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <small class="text-danger">Inputs marked with an asterisk (<span class="text-danger"> * </span>) are mandatory</small>

    <form action="{{ route('equipments.update', $equipment) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color:#510404">
                <h5 class="mb-0">Edit Equipment Information: {{ $equipment->registration_number ?? $equipment->asset_code }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Left Column: Mandatory Inputs -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="asset_code" class="form-label">Asset Code</label>
                            <input type="text" name="asset_code" class="form-control @error('asset_code') is-invalid @enderror"
                                value="{{ old('asset_code', $equipment->asset_code) }}" placeholder="Enter Asset Code" >
                            @error('asset_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                <option value="" {{ old('type', $equipment->type) == '' ? 'selected' : '' }} disabled>Select Equipment Type</option>
                                <option value="HMV" {{ old('type', $equipment->type) == 'HMV' ? 'selected' : '' }}>HMV (Tippers, Low Bed, etc.)</option>
                                <option value="LMV" {{ old('type', $equipment->type) == 'LMV' ? 'selected' : '' }}>LMV (Hilux, SUVs, etc.)</option>
                                <option value="Machinery" {{ old('type', $equipment->type) == 'Machinery' ? 'selected' : '' }}>Machinery (Excavators, Loaders, Generators, etc.)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="ownership" class="form-label">Ownership <span class="text-danger">*</span></label>
                            <input type="text" name="ownership" class="form-control @error('ownership') is-invalid @enderror"
                                value="{{ old('ownership', $equipment->ownership) }}" placeholder="Example: Swarna Metals, PLR..." required>
                            @error('ownership')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="equipment_name" class="form-label">Equipment Name <span class="text-danger">*</span></label>
                            <input type="text" name="equipment_name" class="form-control @error('equipment_name') is-invalid @enderror"
                                value="{{ old('equipment_name', $equipment->equipment_name) }}" placeholder="Enter Equipment Name" required>
                            @error('equipment_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="date_purchased" class="form-label">Date Purchased <span class="text-danger">*</span></label>
                            <input type="date" name="date_purchased" class="form-control @error('date_purchased') is-invalid @enderror"
                                value="{{ old('date_purchased', $equipment->date_purchased->toDateString()) }}" required>
                            @error('date_purchased')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="">Select Status</option>
                                <option value="Running" {{ old('status', $equipment->status) == 'Running' ? 'selected' : '' }}>Running</option>
                                <option value="Under Maintenance" {{ old('status', $equipment->status) == 'Under Maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                <option value="Broken Down" {{ old('status', $equipment->status) == 'Broken Down' ? 'selected' : '' }}>Broken Down</option>
                                <option value="Accident" {{ old('status', $equipment->status) == 'Accident' ? 'selected' : '' }}>Accident</option>
                                <option value="Decommissioned" {{ old('status', $equipment->status) == 'Decommissioned' ? 'selected' : '' }}>Decommissioned</option>
                                <option value="Reserved" {{ old('status', $equipment->status) == 'Reserved' ? 'selected' : '' }}>Reserved</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column: Optional Inputs -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="registration_number" class="form-label">Registration Number</label>
                            <input type="text" name="registration_number" class="form-control @error('registration_number') is-invalid @enderror"
                                value="{{ old('registration_number', $equipment->registration_number) }}" placeholder="Example: CAF 2343 ZM, ABZ 1970">
                            @error('registration_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="chassis_number" class="form-label">Chassis Number</label>
                            <input type="text" name="chassis_number" class="form-control @error('chassis_number') is-invalid @enderror"
                                value="{{ old('chassis_number', $equipment->chassis_number) }}" placeholder="Enter Chassis Number">
                            @error('chassis_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="engine_number" class="form-label">Engine Number</label>
                            <input type="text" name="engine_number" class="form-control @error('engine_number') is-invalid @enderror"
                                value="{{ old('engine_number', $equipment->engine_number) }}" placeholder="Enter Engine Number">
                            @error('engine_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="pictures" class="form-label">Upload Pictures</label>
                            <input type="file" name="pictures[]" class="form-control @error('pictures') is-invalid @enderror" multiple>
                            @if ($equipment->pictures)
                                <small class="text-muted">Current pictures: {{ count(json_decode($equipment->pictures, true)) }} uploaded</small>
                            @endif
                            @error('pictures')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="value" class="form-label">Value (USD) <span class="text-danger">*</span></label>
                            <input type="number" name="value" class="form-control @error('value') is-invalid @enderror"
                                value="{{ old('value', $equipment->value) }}" placeholder="Example: 40000, 76500 ..." required>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="d-flex justify-content-start mt-3">
            <button type="submit" class="btn btn-success mr-2"><i class="fas fa-save"></i> Update</button>
            <a href="{{ route('equipments.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
        </div>
    </form>
</div>
@endsection
