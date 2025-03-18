@extends('layouts.app')

@section('title', 'Equipment Details')

@section('content')
<style>
    .equipment-title {
        font-size: 1.25rem; /* Small screens */
        word-wrap: break-word;
    }
    @media (min-width: 768px) {
        .equipment-title {
            font-size: 1.75rem; /* Medium screens and up */
        }
    }
</style>
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

    <div id="successMessage" class="alert alert-success" style="display: none;"></div>
    <!-- Equipment Header -->
    <h2 class="mb-2 equipment-title">
        Equipment Details For:
        @if ($equipment->registration_number)
            {{ $equipment->registration_number . '-' . $equipment->equipment_name }}
        @else
            {{ $equipment->asset_code . '-' . $equipment->equipment_name ?? 'N/A' }}
        @endif
    </h2>

    <!-- Action Buttons -->
    <div class="d-flex flex-column flex-md-row">
        <a href="{{ route('equipments.edit', $equipment) }}" class="btn btn-warning m-1"><i class="fas fa-edit"></i> Edit</a>
        <button type="button" class="btn add-trip-btn m-1" style="background-color:#510404; color: #fff;" data-equipment-id="{{ $equipment->id }}" data-equipment-type="{{ $equipment->type }}">
            <i class="fas fa-plus-circle"></i> Add Trip
        </button>
        <button type="button" class="btn btn-success m-1" data-bs-toggle="modal" data-bs-target="#reportModal">
            <i class="fas fa-file-alt"></i> Generate Equipment Report
        </button>
    </div>

    <!-- Tabs for Equipment Details -->
    <ul class="nav nav-tabs" id="equipmentTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">Details</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="trips-tab" data-bs-toggle="tab" data-bs-target="#trips" type="button" role="tab" aria-controls="trips" aria-selected="false">Trips /Hours</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="insurances-tab" data-bs-toggle="tab" data-bs-target="#insurances" type="button" role="tab" aria-controls="insurances" aria-selected="false">Insurances</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="taxes-tab" data-bs-toggle="tab" data-bs-target="#taxes" type="button" role="tab" aria-controls="taxes" aria-selected="false">Taxes</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="spares-tab" data-bs-toggle="tab" data-bs-target="#spares" type="button" role="tab" aria-controls="spares" aria-selected="false">Spares</button>
        </li>
    </ul>

    <div class="tab-content" id="equipmentTabsContent">
        <!-- Equipment Details Tab -->
        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
            <div class="card mt-3">
                <div class="card-header" style="background-color:#510404; color: #fff;">
                    <h5 class="mb-0">Equipment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Asset Code:</strong> {{ $equipment->asset_code ?? 'N/A' }}</p>
                            <p><strong>Registration Number:</strong> {{ $equipment->registration_number ?? 'N/A' }}</p>
                            <p><strong>Chassis Number:</strong> {{ $equipment->chassis_number ?? 'N/A' }}</p>
                            <p><strong>Engine Number:</strong> {{ $equipment->engine_number ?? 'N/A' }}</p>
                            <p>
                                @if ($equipment->status == 'Running')
                                    <div class="btn btn-sm" style="background-color: #28a745; color: white; border-radius: 4px; padding: 0.25rem 0.5rem;">{{ $equipment->status }}</div>
                                @elseif ($equipment->status == 'Under Maintenance')
                                    <div class="btn btn-sm" style="background-color: #6c757d; color: white; border-radius: 4px; padding: 0.25rem 0.5rem;">{{ $equipment->status }}</div>
                                @elseif ($equipment->status == 'Broken Down')
                                    <div class="btn btn-sm" style="background-color: #ffc107; color: white; border-radius: 4px; padding: 0.25rem 0.5rem;">{{ $equipment->status }}</div>
                                @elseif ($equipment->status == 'Accident')
                                    <div class="btn btn-sm" style="background-color: #dc3545; color: white; border-radius: 4px; padding: 0.25rem 0.5rem;">{{ $equipment->status }}</div>
                                @else
                                    <div class="btn btn-sm" style="background-color: #6c757d; color: white; border-radius: 4px; padding: 0.25rem 0.5rem;">{{ $equipment->status ?? 'N/A' }}</div>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p ><strong>Type:</strong> {{ $equipment->type }}</p>
                            <p><strong>Equipment Name:</strong> {{ $equipment->equipment_name }}</p>
                            <p><strong>Ownership:</strong> {{ $equipment->ownership }}</p>
                            <p><strong>Year Purchased:</strong> {{ $equipment->date_purchased->format('Y-m-d') }}</p>
                            <p><strong>Value (USD):</strong> {{ number_format($equipment->value, 2) }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6>Pictures</h6>
                        @php
                            $pictures = is_array($equipment->pictures) ? $equipment->pictures : json_decode($equipment->pictures, true);
                        @endphp
                        @if (!empty($pictures) && is_array($pictures))
                            <div class="row">
                                @foreach ($pictures as $picture)
                                    <div class="col-md-3 mb-3">
                                        <img src="{{ asset('storage/' . $picture) }}" alt="Equipment Picture" class="img-thumbnail" style="max-width: 100%; height: auto;">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No pictures available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Trips Tab -->
        {{-- <div class="tab-pane fade" id="trips" role="tabpanel" aria-labelledby="trips-tab">
            <div class="card mt-3">
                <div class="card-header " style="background-color:#510404; color: #fff;">
                    @if ($equipment->type == 'Machinery')
                        <h5 class="mb-0">Machinery Usage (Hours)</h5>
                    @else
                        <h5 class="mb-0">Trips</h5>
                    @endif
                </div>
                <div class="card-body">
                    @if ($equipment->trips->isEmpty())
                        <div class="alert alert-warning">No Trips/Hours available for this equipment.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Departure Date</th>
                                        <th>Return Date</th>
                                        <th>Start Km</th>
                                        <th>Close Km</th>
                                        <th>Distance Travelled</th>
                                        <th>Location</th>
                                        <th>Driver</th>
                                        <th>Material Delivered</th>
                                        <th>Qty (tonnes)</th>
                                        <th>Fuel Records</th>
                                        <th>Total Fuel Used</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($equipment->trips as $trip)
                                        <tr>
                                            <td>{{ $trip->departure_date->format('Y-m-d') }}</td>
                                            <td>{{ $trip->return_date ? $trip->return_date->format('Y-m-d') : '-' }}</td>
                                            <td>{{ number_format($trip->start_kilometers) }}</td>
                                            <td>{{ $trip->end_kilometers ? number_format($trip->end_kilometers) : '-' }}</td>
                                            <td>{{ $trip->end_kilometers && $trip->start_kilometers ? number_format($trip->end_kilometers - $trip->start_kilometers) : '-' }} km</td>
                                            <td>{{ $trip->location }}</td>
                                            <td>{{ $trip->driver->employee_full_name ?? '-' }}</td>
                                            <td>{{ $trip->material_delivered ?? '-' }}</td>
                                            <td>{{ $trip->quantity ? number_format($trip->quantity, 2) : '-' }}</td>
                                            <td>
                                                @if ($trip->fuels->isEmpty())
                                                    <span class="text-muted">No fuel data</span>
                                                @else
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach ($trip->fuels as $fuel)
                                                            <li>{{ number_format($fuel->litres_added, 2) }} Litres at {{ $fuel->refuel_location ?? '-' }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </td>
                                            <td>{{ number_format($trip->fuels->sum('litres_added'), 2) }} Litres</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning updateTripBtn" data-bs-toggle="modal" data-bs-target="#updateTripModal" data-trip-id="{{ $trip->id }}">
                                                    <i class="fas fa-edit"></i> Update
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div> --}}

        <!-- Trips/Machinery Usage Tab -->
        <div class="tab-pane fade" id="trips" role="tabpanel" aria-labelledby="trips-tab">
            <div class="card mt-3">
                <div class="card-header" style="background-color:#510404; color: #fff;">
                    <h5 class="mb-0">{{ $equipment->type === 'machinery' ? 'Machinery Usage' : 'Trips' }}</h5>
                </div>
                <div class="card-body">
                    @if ($equipment->type === 'Machinery')
                        @if ($equipment->machineryUsages->isEmpty())
                            <div class="alert alert-warning">No machinery usage records available for this equipment.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Date</th>
                                            <th>Operator</th>
                                            <th>Start Hours</th>
                                            <th>Closing Hours</th>
                                            <th>Hours Used</th>
                                            <th>Location</th>
                                            <th>Fuel Records</th>
                                            <th>Total Fuel Used</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($equipment->machineryUsages as $usage)
                                            <tr>
                                                <td>{{ $usage->date->format('Y-m-d') }}</td>
                                                <td>{{ $usage->operator->employee_full_name ?? '-' }}</td>
                                                <td>{{ number_format($usage->start_hours, 2) }}</td>
                                                <td>{{ $usage->closing_hours ? number_format($usage->closing_hours, 2) : '-' }}</td>
                                                <td>{{ $usage->closing_hours && $usage->start_hours ? number_format($usage->closing_hours - $usage->start_hours, 2) : '-' }}</td>
                                                <td>{{ $usage->location }}</td>
                                                <td>
                                                    @if ($usage->fuels->isEmpty())
                                                        <span class="text-muted">No fuel data</span>
                                                    @else
                                                        <ul class="list-unstyled mb-0">
                                                            @foreach ($usage->fuels as $fuel)
                                                                <li>{{ number_format($fuel->litres_added, 2) }} Litres at {{ $fuel->refuel_location ?? '-' }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($usage->fuels->sum('litres_added'), 2) }} Litres</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @else
                        @if ($equipment->trips->isEmpty())
                            <div class="alert alert-warning">No trips available for this equipment.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Departure Date</th>
                                            <th>Return Date</th>
                                            <th>Start Km</th>
                                            <th>Close Km</th>
                                            <th>Distance Travelled</th>
                                            <th>Location</th>
                                            <th>Driver</th>
                                            <th>Material Delivered</th>
                                            <th>Qty (tonnes)</th>
                                            <th>Fuel Records</th>
                                            <th>Total Fuel Used</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($equipment->trips as $trip)
                                            <tr>
                                                <td>{{ $trip->departure_date->format('Y-m-d') }}</td>
                                                <td>{{ $trip->return_date ? $trip->return_date->format('Y-m-d') : '-' }}</td>
                                                <td>{{ number_format($trip->start_kilometers) }}</td>
                                                <td>{{ $trip->end_kilometers ? number_format($trip->end_kilometers) : '-' }}</td>
                                                <td>{{ $trip->end_kilometers && $trip->start_kilometers ? number_format($trip->end_kilometers - $trip->start_kilometers) : '-' }} km</td>
                                                <td>{{ $trip->location }}</td>
                                                <td>{{ $trip->driver->employee_full_name ?? '-' }}</td>
                                                <td>{{ $trip->material_delivered ?? '-' }}</td>
                                                <td>{{ $trip->quantity ? number_format($trip->quantity, 2) : '-' }}</td>
                                                <td>
                                                    @if ($trip->fuels->isEmpty())
                                                        <span class="text-muted">No fuel data</span>
                                                    @else
                                                        <ul class="list-unstyled mb-0">
                                                            @foreach ($trip->fuels as $fuel)
                                                                <li>{{ number_format($fuel->litres_added, 2) }} Litres at {{ $fuel->refuel_location ?? '-' }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($trip->fuels->sum('litres_added'), 2) }} Litres</td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning updateTripBtn" data-bs-toggle="modal" data-bs-target="#updateTripModal" data-trip-id="{{ $trip->id }}">
                                                        <i class="fas fa-edit"></i> Update
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Spares Tab -->
        <div class="tab-pane fade" id="spares" role="tabpanel" aria-labelledby="spares-tab">
            <div class="card mt-3">
                <div class="card-header" style="background-color:#510404; color: #fff;">
                    <h5 class="mb-0">Spare Parts</h5>
                </div>

                <a href="{{ route('equipment_spares.create',$equipment->id)}}" class="btn btn-sm w-50 w-md-auto mt-1 me-md-2" style="background-color:#510404; color: #fff; margin-left:6px;"><i class="fas fa-plus-circle"></i> Register Spares</a>

                <div class="card-body">
                    @if ($equipment->spares->isEmpty())
                        <div class="alert alert-warning">No spare parts recorded for this equipment.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Price (ZMW)</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($equipment->spares as $spare)
                                        <tr>
                                            <td>{{ $spare->name }}</td>
                                            <td>{{ number_format($spare->price, 2) }}</td>
                                            <td>{{ number_format($spare->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Insurances Tab -->
        <div class="tab-pane fade" id="insurances" role="tabpanel" aria-labelledby="insurances-tab">
            <div class="card mt-3">
                <div class="card-header" style="background-color:#510404; color: #fff;">
                    <h5 class="mb-0">Insurances</h5>
                </div>

                <a href="{{ route('equipment_insurances.create',$equipment->id)}}" class="btn w-50 w-md-auto mt-1 me-md-2" style="background-color:#510404; color: #fff; margin-left:6px;"><i class="fas fa-plus-circle"></i> Register Insurance</a>

                <div class="card-body">
                    @if ($equipment->equipmentInsurances->isEmpty())
                        <div class="alert alert-warning">No insurance records for this equipment.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Insurance Company</th>
                                        <th>Premium (ZMW)</th>
                                        <th>Phone Number</th>
                                        <th>Address</th>
                                        <th>Expiry Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($equipment->equipmentInsurances as $insurance)
                                        <tr>
                                            <td>{{ $insurance->insurance_company }}</td>
                                            <td>{{ $insurance->premium > 0 ? number_format($insurance->premium, 2) : '-' }}</td>
                                            <td>{{ $insurance->phone_number ?? 'N/A' }}</td>
                                            <td>{{ $insurance->address ?? 'N/A' }}</td>
                                            <td>{{ $insurance->expiry_date->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Taxes Tab -->
        <div class="tab-pane fade" id="taxes" role="tabpanel" aria-labelledby="taxes-tab">
            <div class="card mt-3">
                <div class="card-header" style="background-color:#510404; color: #fff;">
                    <h5 class="mb-0">Taxes</h5>
                </div>

                <a href="{{ route('equipment_taxes.create',$equipment->id)}}" class="btn w-50 w-md-auto mt-1 me-md-2" style="background-color:#510404; color: #fff; margin-left:6px;"><i class="fas fa-plus-circle"></i> Register Tax</a>

                <div class="card-body">
                    @if ($equipment->equipmentTaxes->isEmpty())
                        <div class="alert alert-warning">No tax records for this equipment.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Cost (ZMW)</th>
                                        <th>Expiry Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($equipment->equipmentTaxes as $tax)
                                        <tr>
                                            <td>{{ $tax->name }}</td>
                                            <td>{{ $tax->cost > 0 ? number_format($tax->cost, 2) : '-' }}</td>
                                            <td>{{ $tax->expiry_date->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Trip Modal -->
    <div class="modal fade" id="addTripModal" tabindex="-1" aria-labelledby="addTripModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTripModalLabel">Add New Trip</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addTripForm" action="{{ route('trips.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="equipment_id" id="addEquipmentId" value="{{ $equipment->id }}">

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="add_driver_id" class="form-label">Driver <span class="text-danger">*</span></label>
                                <select name="driver_id" id="add_driver_id" class="form-control @error('driver_id') is-invalid @enderror" required>
                                    <option value="">Select Driver</option>
                                    @foreach (\App\Models\Employee::whereIn('designation', [
                                        'DRIVER',
                                        'OPERATOR_LOADER',
                                        'DRIVER _ HILUX',
                                        'DRIVER_TIPPER',
                                        'OPERATOR_EXCAVATOR',
                                        'DRIVER_WATERBOWSER',
                                        'OPERATOR_ROLLER',
                                        'LOADER _ OPERATOR',
                                        'DRIVER _ CONTAINER'
                                    ])->orderBy('employee_full_name', 'asc')->get() as $driver)
                                        <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                            {{ $driver->employee_full_name }} ({{ $driver->designation }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('driver_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="add_location" class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" name="location" id="add_location" class="form-control @error('location') is-invalid @enderror"
                                       value="{{ old('location') }}" placeholder="example: Kasempa, Serenje, Ndola, Solwezi..." required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="add_departure_date" class="form-label">Departure Date <span class="text-danger">*</span></label>
                                <input type="date" name="departure_date" id="add_departure_date" class="form-control @error('departure_date') is-invalid @enderror"
                                       value="{{ old('departure_date') }}" required>
                                @error('departure_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="add_return_date" class="form-label">Return Date</label>
                                <input type="date" name="return_date" id="add_return_date" class="form-control @error('return_date') is-invalid @enderror"
                                       value="{{ old('return_date') }}">
                                @error('return_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="add_start_kilometers" class="form-label">Start Kilometers <span class="text-danger">*</span></label>
                                <input type="number" name="start_kilometers" id="add_start_kilometers"
                                       class="form-control @error('start_kilometers') is-invalid @enderror"
                                       value="{{ old('start_kilometers') }}" placeholder="example: 54666" required>
                                @error('start_kilometers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="add_end_kilometers" class="form-label">Closing Kilometers</label>
                                <input type="number" name="end_kilometers" id="add_end_kilometers" class="form-control @error('end_kilometers') is-invalid @enderror"
                                       value="{{ old('end_kilometers') }}" placeholder="example: 54777">
                                @error('end_kilometers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="add_material_delivered" class="form-label">Material Delivered</label>
                                <input type="text" name="material_delivered" id="add_material_delivered" class="form-control @error('material_delivered') is-invalid @enderror"
                                       value="{{ old('material_delivered') }}" placeholder="example: copper ore, quarry, blocks...">
                                @error('material_delivered')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="add_quantity" class="form-label">Quantity (tonnes)</label>
                                <input type="number" step="0.01" name="quantity" id="add_quantity" class="form-control @error('quantity') is-invalid @enderror"
                                       value="{{ old('quantity') }}" placeholder="example: 60, 25 ...">
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h4 class="mt-4">Fuel Information</h4>
                        <div id="add-fuel-entries">
                            <div class="fuel-entry row mb-3">
                                <div class="col-12 col-md-5">
                                    <label for="add_litres_added_0" class="form-label">Litres Added <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="fuels[0][litres_added]" id="add_litres_added_0" class="form-control @error('fuels.0.litres_added') is-invalid @enderror"
                                           value="{{ old('fuels.0.litres_added') }}" placeholder="example: 60" required>
                                    @error('fuels.0.litres_added')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-5">
                                    <label for="add_refuel_location_0" class="form-label">Refuel Location</label>
                                    <input type="text" name="fuels[0][refuel_location]" id="add_refuel_location_0" class="form-control @error('fuels.0.refuel_location') is-invalid @enderror"
                                           value="{{ old('fuels.0.refuel_location') }}" placeholder="example: Site, Chimwemwe Meru Station, Kalulushi Meru Station">
                                    @error('fuels.0.refuel_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-fuel-entry" disabled><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary mb-3" id="add-fuel-entry"><i class="fas fa-plus"></i> Add Another Fuel Entry</button>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Trip & Fuel</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Trip Modal -->
    <div class="modal fade" id="updateTripModal" tabindex="-1" aria-labelledby="updateTripModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateTripModalLabel">Edit Trip Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @include('equipments.trip-modal')
            </div>
        </div>
    </div>

    <!-- Modal for Machinery Usage Form -->
    <div class="modal fade" id="logMachineryUsageModal" tabindex="-1" aria-labelledby="logMachineryUsageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#510404">
                    <h5 class="modal-title text-light" id="logMachineryUsageModalLabel">Register Machinery Usage</h5>
                    <button type="button" class="btn-close" style="color: #fff;" id="btn_close_machinery" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('machinery_usages.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="equipment_id" id="addEquipmentId" value="{{ $equipment->id }}">

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="operator_id" class="form-label">Operator <span class="text-danger">*</span></label>
                                <select name="operator_id" id="operator_id" class="form-control @error('operator_id') is-invalid @enderror" required>
                                    <option value="">Select Operator</option>
                                    @foreach (\App\Models\Employee::whereIn('designation', [
                                        'DRIVER',
                                        'OPERATOR_LOADER',
                                        'DRIVER _ HILUX',
                                        'DRIVER_TIPPER',
                                        'OPERATOR_EXCAVATOR',
                                        'DRIVER_WATERBOWSER',
                                        'OPERATOR_ROLLER',
                                        'LOADER _ OPERATOR',
                                        'DRIVER _ CONTAINER'
                                    ])->orderBy('employee_full_name', 'asc')->get() as $driver)
                                        <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                            {{ $driver->employee_full_name }} ({{ $driver->designation }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('operator_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                                    value="{{ old('location') }}" placeholder="example: Site,Kasempa,..." required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                                    value="{{ old('date') }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="start_hours" class="form-label">Start Hours <span class="text-danger">*</span></label>
                                <input type="number"  name="start_hours" id="start_hours" class="form-control @error('start_hours') is-invalid @enderror"
                                    value="{{ old('start_hours') }}" placeholder="example: 85670" required>
                                @error('start_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="closing_hours" class="form-label">Closing Hours <span class="text-danger">*</span></label>
                                <input type="number" name="closing_hours" class="form-control @error('closing_hours') is-invalid @enderror"
                                    value="{{ old('closing_hours') }}" placeholder="example: 85690" required>
                                @error('closing_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h4 class="mt-4">Fuel Information</h4>
                        <div id="machinery-fuel-entries">
                            <div class="fuel-entry row mb-3">
                                <div class="col-12 col-md-5">
                                    <label for="litres_added[]" class="form-label">Litres Added <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="fuels[0][litres_added]" class="form-control @error('fuels.0.litres_added') is-invalid @enderror"
                                        value="{{ old('fuels.0.litres_added') }}" placeholder="example: 230" required>
                                    @error('fuels.0.litres_added')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-5">
                                    <label for="refuel_location[]" class="form-label">Refuel Location</label>
                                    <input type="text" name="fuels[0][refuel_location]" class="form-control @error('fuels.0.refuel_location') is-invalid @enderror"
                                        value="{{ old('fuels.0.refuel_location') }}" placeholder="example: Site">
                                    @error('fuels.0.refuel_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-fuel-entry" disabled><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary mb-3" id="add-machinery-fuel-entry"><i class="fas fa-plus"></i> Add Another Fuel Entry</button>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="btn_close_machinery" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Machinery Usage & Fuel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Equipment Report Modal -->
    {{-- <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Generate Equipment Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('reports.generate') }}" method="POST">
                        @csrf
                        <input type="hidden" id="equipment_id" name="equipment_id" value="{{ $equipment->id }}">
                        <input type="hidden" id="format" name="format" value="csv">

                        <div class="mb-3">
                            <label for="month" class="form-label">Select Month</label>
                            <select class="form-control @error('month') is-invalid @enderror" id="month" name="month" required>
                                <option value="">Select Month</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ old('month') == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                            @error('month')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="year" class="form-label">Select Year</label>
                            <select class="form-control @error('year') is-invalid @enderror" id="year" name="year" required>
                                <option value="">Select Year</option>
                                @for ($y = date('Y'); $y >= date('Y') - 10; $y--)
                                    <option value="{{ $y }}" {{ old('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fas fa-download"></i> Generate Report</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Equipment Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Generate Equipment Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reportForm" action="{{ route('reports.generate') }}" method="POST">
                        @csrf
                        <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">

                        <div class="mb-3">
                            <label for="month" class="form-label">Select Month <span class="text-danger">*</span></label>
                            <select class="form-control @error('month') is-invalid @enderror" id="month" name="month" required>
                                <option value="">Select Month</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ old('month') == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                            @error('month')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="year" class="form-label">Select Year <span class="text-danger">*</span></label>
                            <select class="form-control @error('year') is-invalid @enderror" id="year" name="year" required>
                                <option value="">Select Year</option>
                                @for ($y = date('Y'); $y >= date('Y') - 10; $y--)
                                    <option value="{{ $y }}" {{ old('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="format" class="form-label">Report Format <span class="text-danger">*</span></label>
                            <select class="form-control @error('format') is-invalid @enderror" id="format" name="format" required>
                                <option value="">Select Format</option>
                                <option value="csv" {{ old('format') == 'csv' ? 'selected' : '' }}>CSV</option>
                                {{-- later update pdf format --}}
                                {{-- <option value="pdf" {{ old('format') == 'pdf' ? 'selected' : '' }}>PDF</option> --}}
                            </select>
                            @error('format')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fas fa-download"></i> Generate Report</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript for AJAX and dynamic fuel entries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var fuelEntryCount = 1; // Global scope

    document.addEventListener("DOMContentLoaded", function() {
        var addTripButton = document.querySelector('.add-trip-btn');
        var equipmentIdField = document.getElementById('addEquipmentId');
        var machineryIdField = document.getElementById('selectedMachineryId');
        var tripModalElement = document.getElementById('addTripModal');
        var machineryModalElement = document.getElementById('logMachineryUsageModal');
        var startKilometersField = document.getElementById('add_start_kilometers');
        var startHoursField = document.getElementById('start_hours');
        var fuelEntriesContainer = document.getElementById('add-fuel-entries');
        var addFuelEntryButton = document.getElementById('add-fuel-entry');

        if (addTripButton && equipmentIdField && tripModalElement && startKilometersField) {
            addTripButton.addEventListener('click', function() {
                var equipmentId = this.getAttribute('data-equipment-id');
                var equipmentType = this.getAttribute('data-equipment-type');
                equipmentIdField.value = equipmentId;

                if (equipmentType === 'Machinery') {
                    // machineryIdField.value = this.value;

                    // Fetch the last Machinery Usage's closing_hours for the selected equipment
                    fetch(`/machinery-usages/last-usage/${equipmentId}`)
                        .then(response => response.json())
                        .then(data => {
                            startHoursField.value = data.closing_hours !== null ? data.closing_hours : (data.start_hours || 0);
                        })
                        .catch(error => {
                            console.error('Error fetching last trip details:', error);
                            startHoursField.value = 0; // Default to 0 on error
                        });

                    var modal = new bootstrap.Modal(machineryModalElement);
                    modal.show();
                } else {
                    // equip.value = this.value;

                    // Fetch the last trip's end_kilometers for the selected equipment
                    fetch(`/trips/last-trip/${equipmentId}`)
                        .then(response => response.json())
                        .then(data => {
                            startKilometersField.value = data.end_kilometers !== null ? data.end_kilometers : (data.start_kilometers || 0);
                        })
                        .catch(error => {
                            console.error('Error fetching last trip details:', error);
                            startKilometersField.value = 0; // Default to 0 on error
                        });

                    var modal = new bootstrap.Modal(tripModalElement);
                    modal.show();
                }
            });
        }

        addFuelEntryButton.addEventListener('click', function() {
            var newEntry = `
                <div class="fuel-entry row mb-3">
                    <div class="col-12 col-md-5">
                        <label for="add_litres_added_${fuelEntryCount}" class="form-label">Litres Added <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="fuels[${fuelEntryCount}][litres_added]" id="add_litres_added_${fuelEntryCount}" class="form-control" placeholder="example: 60" required>
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="add_refuel_location_${fuelEntryCount}" class="form-label">Refuel Location</label>
                        <input type="text" name="fuels[${fuelEntryCount}][refuel_location]" id="add_refuel_location_${fuelEntryCount}" class="form-control" placeholder="example: Site, Chimwemwe Meru Station, Kalulushi Meru Station">
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-fuel-entry"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `;
            fuelEntriesContainer.insertAdjacentHTML('beforeend', newEntry);
            fuelEntryCount++;
            updateRemoveButtons();
        });

        fuelEntriesContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-fuel-entry') || e.target.parentElement.classList.contains('remove-fuel-entry')) {
                e.target.closest('.fuel-entry').remove();
                updateRemoveButtons();
            }
        });

        function updateRemoveButtons() {
            var entries = fuelEntriesContainer.getElementsByClassName('fuel-entry');
            var removeButtons = fuelEntriesContainer.getElementsByClassName('remove-fuel-entry');
            for (var i = 0; i < removeButtons.length; i++) {
                removeButtons[i].disabled = (entries.length === 1);
            }
        }
    });


    // ----------------- Update Trip JavaScript --------------------------------
    $(document).ready(function() {
        $(document).on('click', '.updateTripBtn', function() {
            let tripId = $(this).data('trip-id');
            $('#updateTripId').val(tripId);

            console.log('Fetching trip data for ID:', tripId);

            $.ajax({
                url: `/trips/${tripId}/edit`,
                type: "GET",
                success: function(response) {
                    console.log('AJAX Success:', response);
                    $('#driver_id').val(response.driver_id);
                    $('#location').val(response.location);
                    $('#departure_date').val(response.departure_date);
                    $('#return_date').val(response.return_date);
                    $('#start_kilometers').val(response.start_kilometers);
                    $('#end_kilometers').val(response.end_kilometers);
                    $('#material_delivered').val(response.material_delivered);
                    $('#quantity').val(response.quantity);
                    $('#updateEquipmentId').val(response.equipment_id);

                    $('#fuel-entries').empty();
                    fuelEntryCount = 0;
                    response.fuels.forEach(function(fuel) {
                        var newEntry = `
                            <div class="fuel-entry row mb-3">
                                <div class="col-12 col-md-5">
                                    <label for="litres_added_${fuelEntryCount}" class="form-label">Litres Added <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="fuels[${fuelEntryCount}][litres_added]" id="litres_added_${fuelEntryCount}" class="form-control" value="${fuel.litres_added || ''}" placeholder="example: 60" required>
                                </div>
                                <div class="col-12 col-md-5">
                                    <label for="refuel_location_${fuelEntryCount}" class="form-label">Refuel Location</label>
                                    <input type="text" name="fuels[${fuelEntryCount}][refuel_location]" id="refuel_location_${fuelEntryCount}" class="form-control" value="${fuel.refuel_location || ''}" placeholder="example: Site, Chimwemwe Meru Station, Kalulushi Meru Station">
                                </div>
                                <div class="col-12 col-md-2 d-flex align-items-end">
                                    <input type="hidden" name="fuels[${fuelEntryCount}][id]" value="${fuel.id || ''}">
                                    <button type="button" class="btn btn-danger remove-fuel-entry"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        `;
                        $('#fuel-entries').append(newEntry);
                        fuelEntryCount++;
                    });
                    updateRemoveButtons();
                },
                error: function(xhr) {
                    console.error('AJAX Error:', xhr.status, xhr.responseText);
                    $('#successMessage').removeClass('alert-success').addClass('alert-danger')
                        .text('Failed to fetch trip data.').fadeIn();
                }
            });
        });

        $('#add-fuel-entry').on('click', function() {
            var newEntry = `
                <div class="fuel-entry row mb-3">
                    <div class="col-12 col-md-5">
                        <label for="litres_added_${fuelEntryCount}" class="form-label">Litres Added <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="fuels[${fuelEntryCount}][litres_added]" id="litres_added_${fuelEntryCount}" class="form-control" placeholder="example: 60" required>
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="refuel_location_${fuelEntryCount}" class="form-label">Refuel Location</label>
                        <input type="text" name="fuels[${fuelEntryCount}][refuel_location]" id="refuel_location_${fuelEntryCount}" class="form-control" placeholder="example: Site, Chimwemwe Meru Station, Kalulushi Meru Station">
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-fuel-entry"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `;
            $('#fuel-entries').append(newEntry);
            fuelEntryCount++;
            updateRemoveButtons();
        });

        $('#fuel-entries').on('click', '.remove-fuel-entry', function() {
            $(this).closest('.fuel-entry').remove();
            updateRemoveButtons();
        });

        function updateRemoveButtons() {
            var entries = $('#fuel-entries .fuel-entry');
            var removeButtons = $('#fuel-entries .remove-fuel-entry');
            removeButtons.prop('disabled', entries.length === 1);
        }

        $('#updateTripForm').on('submit', function(e) {
            e.preventDefault();
            let tripId = $('#updateTripId').val();
            let formData = $(this).serializeArray();
            let jsonData = {};

            $.each(formData, function() {
                if (this.name.includes('[')) {
                    let [mainKey, index, subKey] = this.name.match(/([^[\]]+)/g);
                    if (!jsonData[mainKey]) jsonData[mainKey] = [];
                    if (!jsonData[mainKey][index]) jsonData[mainKey][index] = {};
                    jsonData[mainKey][index][subKey] = this.value || null;
                } else {
                    jsonData[this.name] = this.value || null;
                }
            });

            $.ajax({
                url: `/trips/${tripId}`,
                type: "PUT",
                data: JSON.stringify(jsonData),
                contentType: "application/json",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $('#successMessage').text(response.message).fadeIn().delay(3000).fadeOut();
                        $('#updateTripModal').modal('hide');
                        setTimeout(() => location.reload(), 3000);
                    } else {
                        $('#successMessage').removeClass('alert-success').addClass('alert-danger')
                            .text('Failed to update trip.').fadeIn();
                    }
                },
                error: function(xhr) {
                    console.error('Submit Error:', xhr.status, xhr.responseText);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '<ul>';
                        $.each(errors, function(key, messages) {
                            errorMessage += `<li>${messages[0]}</li>`;
                        });
                        errorMessage += '</ul>';
                        $('#successMessage').removeClass('alert-success').addClass('alert-danger')
                            .html(errorMessage).fadeIn();
                    } else {
                        $('#successMessage').removeClass('alert-success').addClass('alert-danger')
                            .text('An error occurred. Please try again.').fadeIn();
                    }
                }
            });
        });
    });
</script>

@endsection
