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
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($equipment->machineryUsages as $usage)
                                            <tr>
                                                <td>{{ $usage->date->format('Y-m-d') }}</td>
                                                <td>{{ $usage->operator->employee_full_name ?? '-' }}</td>
                                                <td>{{ $usage->start_hours !== null && $usage->start_hours > 0 ? number_format($usage->start_hours, 2) : '-' }}</td>
                                                <td>{{ $usage->closing_hours !== null && $usage->closing_hours > 0 ? number_format($usage->closing_hours, 2) : '-' }}</td>
                                                <td>
                                                    @if ($usage->closing_hours !== null && $usage->start_hours !== null && $usage->closing_hours > 0 && $usage->start_hours > 0 && $usage->closing_hours > $usage->start_hours)
                                                        {{ number_format($usage->closing_hours - $usage->start_hours, 2) }} hrs
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $usage->location ?? '-' }}</td>
                                                <td>
                                                    @if ($usage->fuels->isEmpty())
                                                        <span class="text-muted small">No fuel data available</span>
                                                    @else
                                                        <ul class="list-unstyled mb-0">
                                                            @foreach ($usage->fuels as $fuel)
                                                                <li>
                                                                    {{ number_format($fuel->litres_added, 2) }} Litres
                                                                    @if ($fuel->refuel_location)
                                                                        <span class="text-muted">at {{ $fuel->refuel_location }}</span>
                                                                    @endif
                                                                    @if ($fuel->cost !== null)
                                                                        <span class="text-muted">({{ number_format($fuel->cost, 2) }} ZMW)</span>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </td>
                                                @php
                                                    $totalFuelCost = $usage->fuels->sum(fn($fuel) => $fuel->cost !== null ? $fuel->litres_added * $fuel->cost : 0);
                                                @endphp
                                                <td>{{ number_format($usage->fuels->sum('litres_added'), 2) }} Litres
                                                <span class="text-muted">({{ $totalFuelCost > 0 ? number_format($totalFuelCost, 2) . ' ZMW' : '-' }})</span>
                                                </td>
                                                <td>
                                                    <a class="btn btn-sm btn-warning" href="{{ route('machinery_usages.edit', $usage->id) }}"><i class="fas fa-edit"></i> Edit</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">No machinery usage recorded for this equipment.</td>
                                            </tr>
                                        @endforelse
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
                                            <th>Supplier Name</th>
                                            <th>Gross Wt (tonnes)</th>
                                            <th>Tare Wt (tonnes)</th>
                                            <th>Net Wt (tonnes)</th>
                                            <th>Loading Cost (ZMW)</th>
                                            <th>Council Fee (ZMW)</th>
                                            <th>Weighbridge Fee (ZMW)</th>
                                            <th>Toll Gate Fee (ZMW)</th>
                                            <th>Other Expenses (ZMW)</th>
                                            <th>Fuel Records</th>
                                            <th>Total Fuel Used</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($equipment->trips as $trip)
                                            <tr>
                                                <td>{{ $trip->departure_date->format('Y-m-d') }}</td>
                                                <td>{{ $trip->return_date ? $trip->return_date->format('Y-m-d') : '-' }}</td>
                                                <td>{{ $trip->start_kilometers !== null && $trip->start_kilometers > 0 ? number_format($trip->start_kilometers, 2) : '-' }}</td>
                                                <td>{{ $trip->end_kilometers !== null && $trip->end_kilometers > 0 ? number_format($trip->end_kilometers, 2) : '-' }}</td>
                                                <td>
                                                    @if ($trip->end_kilometers !== null && $trip->start_kilometers !== null && $trip->end_kilometers > 0 && $trip->start_kilometers > 0 && $trip->end_kilometers > $trip->start_kilometers)
                                                        {{ number_format($trip->end_kilometers - $trip->start_kilometers, 2) }} km
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $trip->location ?? '-' }}</td>
                                                <td>{{ $trip->driver->employee_full_name ?? '-' }}</td>
                                                <td>{{ $trip->material_delivered ?? '-' }}</td>
                                                <td>{{ $trip->supplier_name ?? '-' }}</td>
                                                <td>{{ $trip->gross_weight !== null ? number_format($trip->gross_weight, 2) : '-' }}</td>
                                                <td>{{ $trip->tare_weight !== null ? number_format($trip->tare_weight, 2) : '-' }}</td>
                                                <td>{{ $trip->net_weight !== null && $trip->net_weight > 0 ? number_format($trip->net_weight, 2) : '-' }}</td>
                                                <td>{{ $trip->loading !== null ? number_format($trip->loading, 2) : '-' }}</td>
                                                <td>{{ $trip->council_fee !== null ? number_format($trip->council_fee, 2) : '-' }}</td>
                                                <td>{{ $trip->weighbridge !== null ? number_format($trip->weighbridge, 2) : '-' }}</td>
                                                <td>{{ $trip->toll_gate !== null ? number_format($trip->toll_gate, 2) : '-' }}</td>
                                                <td>{{ $trip->other_expenses !== null ? number_format($trip->other_expenses, 2) : '-' }}</td>
                                                <td>
                                                    @if ($trip->fuels->isEmpty())
                                                        <span class="text-muted small">No fuel data available</span>
                                                    @else
                                                        <ul class="list-unstyled mb-0">
                                                            @foreach ($trip->fuels as $fuel)
                                                                <li>
                                                                    {{ number_format($fuel->litres_added, 2) }} Litres
                                                                    @if ($fuel->refuel_location)
                                                                        <span class="text-muted">at {{ $fuel->refuel_location }}</span>
                                                                    @endif
                                                                    @if ($fuel->cost !== null && $fuel->cost !== 0)
                                                                        <span class="text-muted">({{ number_format($fuel->cost, 2) }} ZMW/L)</span>
                                                                    @else
                                                                        <span class="text-muted">(-)</span>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($trip->fuels->isEmpty())
                                                        <span class="text-muted">-</span>
                                                    @else
                                                        <?php
                                                            $totalFuelCost = $trip->fuels->sum(function ($fuel) {
                                                                return $fuel->cost !== null ? $fuel->litres_added * $fuel->cost : 0;
                                                            });
                                                        ?>
                                                        {{ number_format($trip->fuels->sum('litres_added'), 2) }} Litres
                                                        <span class="text-muted">({{ $totalFuelCost > 0 ? number_format($totalFuelCost, 2) . ' ZMW' : '-' }})</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning updateTripBtn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#updateTripModal"
                                                            data-trip-id="{{ $trip->id }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="20" class="text-center text-muted">No trips recorded for this equipment.</td>
                                            </tr>
                                        @endforelse
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
                                <input type="number" step="0.01" name="start_kilometers" id="add_start_kilometers"
                                       class="form-control @error('start_kilometers') is-invalid @enderror"
                                       value="{{ old('start_kilometers') }}" placeholder="example: 54666" required>
                                @error('start_kilometers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="add_end_kilometers" class="form-label">Closing Kilometers</label>
                                <input type="number" step="0.01" name="end_kilometers" id="add_end_kilometers" class="form-control @error('end_kilometers') is-invalid @enderror"
                                       value="{{ old('end_kilometers') }}" placeholder="example: 54777">
                                @error('end_kilometers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="material_delivered" class="form-label">Material Delivered</label>
                                <input type="text" name="material_delivered" class="form-control @error('material_delivered') is-invalid @enderror"
                                    value="{{ old('material_delivered') }}" placeholder="example: copper ore, quarry, blocks...">
                                @error('material_delivered')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="supplier_name" class="form-label">Supplier Name</label>
                                <input type="text" name="supplier_name" id="supplier_name" class="form-control @error('supplier_name') is-invalid @enderror"
                                    value="{{ old('supplier_name') }}" placeholder="e.g., ABC Mining Co.">
                                @error('supplier_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="gross_weight" class="form-label">Gross Weight (tonnes)</label>
                                <input type="number" name="gross_weight" id="gross_weight" class="form-control @error('gross_weight') is-invalid @enderror"
                                    step="0.01" value="{{ old('gross_weight') }}" placeholder="e.g., 80.50">
                                @error('gross_weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="tare_weight" class="form-label">Tare Weight (tonnes)</label>
                                <input type="number" name="tare_weight" id="tare_weight" class="form-control @error('tare_weight') is-invalid @enderror"
                                    step="0.01" value="{{ old('tare_weight') }}" placeholder="e.g., 20.25">
                                @error('tare_weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="net_weight" class="form-label">Net Weight (tonnes)</label>
                                <input type="number" name="net_weight" id="net_weight" class="form-control @error('net_weight') is-invalid @enderror"
                                    step="0.01" value="{{ old('net_weight') }}" placeholder="e.g., 60.25">
                                @error('net_weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">

                            </div>
                        </div>

                        <!-- Trip Expense Fields -->
                        <h4 class="mt-4">Trip Expenses</h4>
                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="loading" class="form-label">Loading Cost</label>
                                <input type="number" name="loading" id="loading" class="form-control @error('loading') is-invalid @enderror"
                                    step="0.01" value="{{ old('loading') }}" placeholder="e.g., 150.50">
                                @error('loading')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="council_fee" class="form-label">Council Fee</label>
                                <input type="number" name="council_fee" id="council_fee" class="form-control @error('council_fee') is-invalid @enderror"
                                    step="0.01" value="{{ old('council_fee') }}" placeholder="e.g., 75.00">
                                @error('council_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="weighbridge" class="form-label">Weighbridge Fee</label>
                                <input type="number" name="weighbridge" id="weighbridge" class="form-control @error('weighbridge') is-invalid @enderror"
                                    step="0.01" value="{{ old('weighbridge') }}" placeholder="e.g., 20.50">
                                @error('weighbridge')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="toll_gate" class="form-label">Toll Gate Fee</label>
                                <input type="number" name="toll_gate" id="toll_gate" class="form-control @error('toll_gate') is-invalid @enderror"
                                    step="0.01" value="{{ old('toll_gate') }}" placeholder="e.g., 50.25">
                                @error('toll_gate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="other_expenses" class="form-label">Other Expenses</label>
                                <input type="number" name="other_expenses" id="other_expenses" class="form-control @error('other_expenses') is-invalid @enderror"
                                    step="0.01" value="{{ old('other_expenses') }}" placeholder="e.g., 100.00">
                                @error('other_expenses')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">

                            </div>
                        </div>

                        <!-- Trip Fuel Fields -->
                        <h4 class="mt-4">Fuel Information</h4>
                        <div id="add-fuel-entries">
                            <div class="fuel-entry row mb-3">
                                <div class="col-12 col-md-4">
                                    <label for="add_litres_added_0" class="form-label">Litres Added <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="fuels[0][litres_added]" id="add_litres_added_0" class="form-control @error('fuels.0.litres_added') is-invalid @enderror"
                                        value="{{ old('fuels.0.litres_added') }}" placeholder="example: 60" required>
                                    @error('fuels.0.litres_added')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="add_cost_0" class="form-label">Cost per Litre (ZMW)</label>
                                    <input type="number" step="0.01" name="fuels[0][cost]" id="add_cost_0" class="form-control @error('fuels.0.cost') is-invalid @enderror"
                                        value="{{ old('fuels.0.cost') }}" placeholder="example: 30.23">
                                    @error('fuels.0.cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="add_refuel_location_0" class="form-label">Refuel Location</label>
                                    <input type="text" name="fuels[0][refuel_location]" id="add_refuel_location_0" class="form-control @error('fuels.0.refuel_location') is-invalid @enderror"
                                        value="{{ old('fuels.0.refuel_location') }}" placeholder="example: Site, Chimwemwe Meru Station">
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
                @if ($equipment->type === 'HMV' || $equipment->type === 'LMV')
                    @include('equipments.trip-modal')
                @endif
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
                                <input type="number" step="0.01" name="start_hours" id="start_hours" class="form-control @error('start_hours') is-invalid @enderror"
                                    value="{{ old('start_hours') }}" placeholder="example: 85670" required>
                                @error('start_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="closing_hours" class="form-label">Closing Hours <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="closing_hours" class="form-control @error('closing_hours') is-invalid @enderror"
                                    value="{{ old('closing_hours') }}" placeholder="example: 85690" required>
                                @error('closing_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h4 class="mt-4">Fuel Information</h4>
                        <div id="machinery-fuel-entries">
                            <div class="fuel-entry row mb-3">
                                <div class="col-12 col-md-4">
                                    <label for="machinery_litres_added_0" class="form-label">Litres Added <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="fuels[0][litres_added]" id="machinery_litres_added_0" class="form-control @error('fuels.0.litres_added') is-invalid @enderror"
                                        value="{{ old('fuels.0.litres_added') }}" placeholder="example: 60" required>
                                    @error('fuels.0.litres_added')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="machinery_cost_0" class="form-label">Cost per Litre (ZMW)</label>
                                    <input type="number" step="0.01" name="fuels[0][cost]" id="machinery_cost_0" class="form-control @error('fuels.0.cost') is-invalid @enderror"
                                        value="{{ old('fuels.0.cost') }}" placeholder="example: 30.23">
                                    @error('fuels.0.cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="machinery_refuel_location_0" class="form-label">Refuel Location</label>
                                    <input type="text" name="fuels[0][refuel_location]" id="machinery_refuel_location_0" class="form-control @error('fuels.0.refuel_location') is-invalid @enderror"
                                        value="{{ old('fuels.0.refuel_location') }}" placeholder="example: Site, Chimwemwe Meru Station">
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
                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" >
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}" >
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="format" class="form-label">Report Format <span class="text-danger">*</span></label>
                            <select class="form-control @error('format') is-invalid @enderror" id="format" name="format" required>
                                <option value="">Select Format</option>
                                <option value="csv" {{ old('format') == 'csv' ? 'selected' : '' }}>Excel (CSV)</option>
                                {{-- <option value="pdf" {{ old('format') == 'pdf' ? 'selected' : '' }}>PDF</option> <!-- later Update PDF option --> --}}
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
    document.addEventListener("DOMContentLoaded", function() {
        let tripFuelEntryCount = 1; // Start at 1 since fuels[0] exists
        let machineryFuelEntryCount = 1;

        const addTripButton = document.querySelector('.add-trip-btn');
        const equipmentIdField = document.getElementById('addEquipmentId');
        const machineryIdField = document.getElementById('selectedMachineryId');
        const tripModalElement = document.getElementById('addTripModal');
        const machineryModalElement = document.getElementById('logMachineryUsageModal');
        const startKilometersField = document.getElementById('add_start_kilometers');
        const startHoursField = document.getElementById('start_hours');
        const fuelEntriesContainer = document.getElementById('add-fuel-entries');
        const addFuelEntryButton = document.getElementById('add-fuel-entry');
        const machineryFuelEntriesContainer = document.getElementById('machinery-fuel-entries');
        const machineryAddFuelEntryButton = document.getElementById('add-machinery-fuel-entry');

        if (addTripButton && equipmentIdField && tripModalElement && startKilometersField && machineryModalElement && startHoursField) {
            addTripButton.addEventListener('click', function() {
                const equipmentId = this.getAttribute('data-equipment-id');
                const equipmentType = this.getAttribute('data-equipment-type');
                equipmentIdField.value = equipmentId;

                if (equipmentType === 'Machinery') {
                    fetch(`/machinery-usages/last-usage/${equipmentId}`)
                        .then(response => response.json())
                        .then(data => {
                            startHoursField.value = data.closing_hours !== null ? data.closing_hours : (data.start_hours || 0);
                        })
                        .catch(error => {
                            console.error('Error fetching last usage details:', error);
                            startHoursField.value = 0;
                            alert('Failed to fetch last usage data. Defaulting to 0 hours.');
                        });

                    const modal = new bootstrap.Modal(machineryModalElement);
                    modal.show();
                } else {
                    fetch(`/trips/last-trip/${equipmentId}`)
                        .then(response => response.json())
                        .then(data => {
                            startKilometersField.value = data.end_kilometers !== null ? data.end_kilometers : (data.start_kilometers || 0);
                        })
                        .catch(error => {
                            console.error('Error fetching last trip details:', error);
                            startKilometersField.value = 0;
                            alert('Failed to fetch last trip data. Defaulting to 0 kilometers.');
                        });

                    const modal = new bootstrap.Modal(tripModalElement);
                    modal.show();
                }
            });
        }

        function createFuelEntry(container, context) {
            if (!container) return; // Guard against null container
            const count = context === 'trip' ? tripFuelEntryCount++ : machineryFuelEntryCount++;
            const idPrefix = context === 'trip' ? 'add_' : 'machinery_';

            const newEntry = `
                <div class="fuel-entry row mb-3">
                    <div class="col-12 col-md-4">
                        <label for="${idPrefix}litres_added_${count}" class="form-label">Litres Added <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="fuels[${count}][litres_added]" id="${idPrefix}litres_added_${count}" class="form-control" placeholder="example: 60" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="${idPrefix}cost_${count}" class="form-label">Cost per Litre (ZMW)</label>
                        <input type="number" step="0.01" name="fuels[${count}][cost]" id="${idPrefix}cost_${count}" class="form-control" placeholder="example: 30.23">
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="${idPrefix}refuel_location_${count}" class="form-label">Refuel Location</label>
                        <input type="text" name="fuels[${count}][refuel_location]" id="${idPrefix}refuel_location_${count}" class="form-control" placeholder="example: Site, Chimwemwe Meru Station">
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-fuel-entry"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', newEntry);
            updateRemoveButtons(container);
        }

        function updateRemoveButtons(container) {
            if (!container) return;
            const entries = container.getElementsByClassName('fuel-entry');
            const removeButtons = container.getElementsByClassName('remove-fuel-entry');
            for (let button of removeButtons) {
                button.disabled = entries.length <= 1;
            }
        }

        if (addFuelEntryButton && fuelEntriesContainer) {
            addFuelEntryButton.addEventListener('click', () => createFuelEntry(fuelEntriesContainer, 'trip'));
        }

        if (machineryAddFuelEntryButton && machineryFuelEntriesContainer) {
            machineryAddFuelEntryButton.addEventListener('click', () => createFuelEntry(machineryFuelEntriesContainer, 'machinery'));
        }

        [fuelEntriesContainer, machineryFuelEntriesContainer].forEach(container => {
            if (!container) return; // Skip if container is null
            container.addEventListener('click', function(e) {
                const button = e.target.closest('.remove-fuel-entry');
                if (button) {
                    const fuelEntry = button.closest('.fuel-entry');
                    if (fuelEntry && container.getElementsByClassName('fuel-entry').length > 1) {
                        fuelEntry.remove();
                        updateRemoveButtons(container);
                    }
                }
            });
        });

        // Net weight calculation
        const tripForm = tripModalElement ? tripModalElement.querySelector('form') : null;
        if (tripForm) {
            tripForm.addEventListener('input', function(e) {
                if (e.target.id === 'gross_weight' || e.target.id === 'tare_weight') {
                    const gross = parseFloat(document.getElementById('gross_weight').value) || 0;
                    const tare = parseFloat(document.getElementById('tare_weight').value) || 0;
                    document.getElementById('net_weight').value = (gross - tare).toFixed(2);
                }
            });
        }
    });

    // ----------------- Update Trip JavaScript --------------------------------
    $(document).ready(function() {
        let fuelEntryCount = @if(isset($trip)) {{ $trip->fuels->count() ?: 1 }} @else 1 @endif;

        $(document).on('click', '.updateTripBtn', function() {
            let tripId = $(this).data('trip-id');
            $('#updateTripId').val(tripId);

            console.log('Fetching trip data for ID:', tripId);

            $.ajax({
                url: `/trips/${tripId}/edit`,
                type: "GET",
                success: function(response) {
                    console.log('AJAX Success:', response);
                    let $modal = $('#updateTripForm');

                    $modal.find('#driver_id').val(response.driver_id || '');
                    $modal.find('#location').val(response.location || '');
                    $modal.find('#departure_date').val(response.departure_date || '');
                    $modal.find('#return_date').val(response.return_date || '');
                    $modal.find('#start_kilometers').val(response.start_kilometers || '');
                    $modal.find('#end_kilometers').val(response.end_kilometers || '');
                    $modal.find('#material_delivered').val(response.material_delivered || '');
                    $modal.find('#quantity').val(response.quantity || '');
                    $modal.find('#updateEquipmentId').val(response.equipment_id || '');
                    $modal.find('#supplier_name').val(response.supplier_name || '');
                    $modal.find('#gross_weight').val(response.gross_weight || '');
                    $modal.find('#net_weight').val(response.net_weight || '');
                    $modal.find('#tare_weight').val(response.tare_weight || '');
                    $modal.find('#loading').val(response.loading || '');
                    $modal.find('#council_fee').val(response.council_fee || '');
                    $modal.find('#weighbridge').val(response.weighbridge || '');
                    $modal.find('#toll_gate').val(response.toll_gate || '');
                    $modal.find('#other_expenses').val(response.other_expenses || '');

                    $('#fuel-entries-edit-trip').empty();
                    fuelEntryCount = 0;
                    response.fuels.forEach(function(fuel) {
                        var newEntry = `
                            <div class="fuel-entry row mb-3">
                                <div class="col-12 col-md-4">
                                    <label for="litres_added_${fuelEntryCount}" class="form-label">Litres Added <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="fuels[${fuelEntryCount}][litres_added]" id="litres_added_${fuelEntryCount}" class="form-control" value="${fuel.litres_added || ''}" placeholder="example: 60" required>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="cost_${fuelEntryCount}" class="form-label">Cost per Litre (ZMW)</label>
                                    <input type="number" step="0.01" name="fuels[${fuelEntryCount}][cost]" id="cost_${fuelEntryCount}" class="form-control" value="${fuel.cost || ''}" placeholder="example: 30.23">
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="refuel_location_${fuelEntryCount}" class="form-label">Refuel Location</label>
                                    <input type="text" name="fuels[${fuelEntryCount}][refuel_location]" id="refuel_location_${fuelEntryCount}" class="form-control" value="${fuel.refuel_location || ''}" placeholder="example: Site, Chimwemwe Meru Station">
                                </div>
                                <div class="col-12 col-md-2 d-flex align-items-end">
                                    <input type="hidden" name="fuels[${fuelEntryCount}][id]" value="${fuel.id || ''}">
                                    <button type="button" class="btn btn-danger remove-fuel-entry"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        `;
                        $('#fuel-entries-edit-trip').append(newEntry);
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

        $('#add-fuel-entry-edit-trip').on('click', function() {
            var newEntry = `
                <div class="fuel-entry row mb-3">
                    <div class="col-12 col-md-4">
                        <label for="litres_added_${fuelEntryCount}" class="form-label">Litres Added <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="fuels[${fuelEntryCount}][litres_added]" id="litres_added_${fuelEntryCount}" class="form-control" placeholder="example: 60" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="cost_${fuelEntryCount}" class="form-label">Cost per Litre (ZMW)</label>
                        <input type="number" step="0.01" name="fuels[${fuelEntryCount}][cost]" id="cost_${fuelEntryCount}" class="form-control" placeholder="example: 30.23">
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="refuel_location_${fuelEntryCount}" class="form-label">Refuel Location</label>
                        <input type="text" name="fuels[${fuelEntryCount}][refuel_location]" id="refuel_location_${fuelEntryCount}" class="form-control" placeholder="example: Site, Chimwemwe Meru Station">
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-fuel-entry"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `;
            $('#fuel-entries-edit-trip').append(newEntry);
            fuelEntryCount++;
            updateRemoveButtons();
        });

        $('#fuel-entries-edit-trip').on('click', '.remove-fuel-entry', function() {
            $(this).closest('.fuel-entry').remove();
            updateRemoveButtons();
        });

        function updateRemoveButtons() {
            var entries = $('#fuel-entries-edit-trip .fuel-entry');
            var removeButtons = $('#fuel-entries-edit-trip .remove-fuel-entry');
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
