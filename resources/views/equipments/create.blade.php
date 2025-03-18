@extends('layouts.app')

@section('title', 'Create Equipment')

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

    <form action="{{ route('equipments.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color:#510404">
                <h5 class="mb-0">Register New Equipment</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Left Column: Mandatory Inputs -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="asset_code" class="form-label">Asset Code</label>
                            <input type="text" name="asset_code" class="form-control @error('asset_code') is-invalid @enderror"
                                value="{{ old('asset_code') }}" placeholder="Enter Asset Code" >
                            @error('asset_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                <option value="" {{ old('type') == '' ? 'selected' : '' }} disabled>Select Equipment Type</option>
                                <option value="HMV" {{ old('type') == 'HMV' ? 'selected' : '' }}>HMV (Tippers, Low Bed, etc.)</option>
                                <option value="LMV" {{ old('type') == 'LMV' ? 'selected' : '' }}>LMV (Hilux, SUVs, etc.)</option>
                                <option value="Machinery" {{ old('type') == 'Machinery' ? 'selected' : '' }}>Machinery (Excavators, Loaders, Generators, etc.)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="ownership" class="form-label">Ownership <span class="text-danger">*</span></label>
                            <input type="text" name="ownership" class="form-control @error('ownership') is-invalid @enderror"
                                value="{{ old('ownership') }}" placeholder="Example: Swarna Metals, PLR..." required>
                            @error('ownership')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="equipment_name" class="form-label">Equipment Name <span class="text-danger">*</span></label>
                            <input type="text" name="equipment_name" class="form-control @error('equipment_name') is-invalid @enderror"
                                value="{{ old('equipment_name') }}" placeholder="Example: Tipper,Excavator,Hilux..." required>
                            @error('equipment_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="date_purchased" class="form-label">Year Purchased <span class="text-danger">*</span></label>
                            <input type="date" name="date_purchased" class="form-control @error('date_purchased') is-invalid @enderror"
                                value="{{ old('date_purchased') }}" required>
                            @error('date_purchased')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="">Select Status</option>
                                <option value="Running" {{ old('status') == 'Running' ? 'selected' : '' }}>Running</option>
                                <option value="Under Maintenance" {{ old('status') == 'Under Maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                <option value="Broken Down" {{ old('status') == 'Broken Down' ? 'selected' : '' }}>Broken Down</option>
                                <option value="Accident" {{ old('status') == 'Accident' ? 'selected' : '' }}>Accident</option>
                                <option value="Decommissioned" {{ old('status') == 'Decommissioned' ? 'selected' : '' }}>Decommissioned</option>
                                <option value="Reserved" {{ old('status') == 'Reserved' ? 'selected' : '' }}>Reserved</option>
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
                                value="{{ old('registration_number') }}" placeholder="Example: CAF 2343 ZM, ABZ 1970">
                            @error('registration_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="chasis_number" class="form-label">Chassis Number</label>
                            <input type="text" name="chassis_number" class="form-control @error('chassis_number') is-invalid @enderror"
                                value="{{ old('chassis_number') }}" placeholder="Enter Chassis Number">
                            @error('chassis_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="engine_number" class="form-label">Engine Number</label>
                            <input type="text" name="engine_number" class="form-control @error('engine_number') is-invalid @enderror"
                                value="{{ old('engine_number') }}" placeholder="Enter Engine Number">
                            @error('engine_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="pictures" class="form-label">Upload Pictures</label>
                            <input type="file" name="pictures[]" class="form-control @error('pictures') is-invalid @enderror" multiple>
                            @error('pictures')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="value" class="form-label">Value (USD) <span class="text-danger">*</span></label>
                            <input type="number" name="value" class="form-control @error('value') is-invalid @enderror"
                                value="{{ old('value') }}" placeholder="Example: 40000, 76500 ..." required>
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-3">
            <button type="submit" class="btn btn-success mr-2"><i class="fas fa-save"></i> Save</button>
            <a href="{{ route('equipments.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
        </div>
    </form>
</div>
@endsection
