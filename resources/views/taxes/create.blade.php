@extends('layouts.app')

@section('title', 'Register Equipment Tax')

@section('content')
<div class="container">
    <h1>Register Tax for {{ $equipment->registration_number ?? $equipment->asset_code ??  'Equipment' }}-{{$equipment->equipment_name }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <small class="text-danger">Inputs marked with an asterisk (<span class="text-danger"> * </span>) are mandatory</small>

    <form action="{{ route('equipment_taxes.store') }}" method="POST">
        @csrf
        <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">

        <div class="card mb-4">
            <div class="card-header text-white" style="background-color:#510404">
                <h5 class="mb-0">Tax Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Tax Name <span class="text-danger">*</span></label>
                            <select id="name" name="name" class="form-control @error('name') is-invalid @enderror" required>
                                <option value="">-- Select Tax Type --</option>
                                <option value="ROAD TAX" {{ old('name') == 'ROAD TAX' ? 'selected' : '' }}>ROAD TAX</option>
                                <option value="FITNESS TAX" {{ old('name') == 'FITNESS TAX' ? 'selected' : '' }}>FITNESS TAX</option>
                                <option value="IDENTITY TAX" {{ old('name') == 'IDENTITY TAX' ? 'selected' : '' }}>IDENTITY TAX</option>
                            </select>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cost">Cost (ZMW) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="cost" name="cost" class="form-control @error('cost') is-invalid @enderror"
                                   value="{{ old('cost') }}" placeholder="e.g., 150.00" required>
                            @error('cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="expiry_date">Expiry Date <span class="text-danger">*</span></label>
                            <input type="date" id="expiry_date" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror"
                                   value="{{ old('expiry_date') }}" required>
                            @error('expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Register Tax</button>
            <a href="{{ route('equipments.show', $equipment->id) }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
        </div>
    </form>
</div>
@endsection
