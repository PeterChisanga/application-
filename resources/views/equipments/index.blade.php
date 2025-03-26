@extends('layouts.app')

@section('title', 'Vehicles')

@section('content')
<style>
    .btn-xs {
        padding: 0.125rem 0.25rem;
        font-size: 0.75rem;
        line-height: 1.2;
    }
</style>
<div class="container">
    <h2 class="mb-4">Equipments List</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session()->has('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="d-flex  mb-3">
        <a href="{{ route('equipments.create') }}" class="btn" style="background-color:#510404; margin-left:6px; color: #fff;">
            <i class="fas fa-truck"></i> Register Equipment
        </a>
        {{-- <a href="{{ route('equipments.upload') }}" class="btn btn-success"> <i class="fas fa-upload"></i> Add Equipments With An Excel Sheet</a> --}}
        {{-- <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reportModal">
            <i class="fas fa-file-alt"></i> Generate Report For All Vehicles
        </button> --}}
    </div>

    <!-- Search Form Later Update-->
    {{-- <form action="{{ route('vehicles.index') }}" method="GET" class="mb-3">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by Reg Number, Type, or Driver" value="{{ request('search') }}">
            </div>
            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
            <div class="col-12 col-md-2">
                <a href="{{ route('vehicles.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </div>
    </form> --}}

    <!-- Dropdown to select vehicle -->
    <div class="mb-3">
        <select class="form-control" id="vehicleSelect">
            <option value="">Select an Equipement to Register a Trip/ Machinery Usage</option>
            @foreach ($equipments as $equipment)
                <option value="{{ $equipment->id }}" data-equipment-type="{{ $equipment->type }}">
                    {{ $equipment->registration_number ?? $equipment->asset_code }} - {{ $equipment->equipment_name }}
                </option>
            @endforeach
        </select>
    </div>

    @if($equipments->isEmpty())
        <p class="text-center mt-4">No equipment available.</p>
    @else
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Registration Number</th>
                        <th>Equipment Name</th>
                        <th>Type</th>
                        <th>Mileage (Km) / Hours</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipments as $equipment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $equipment->registration_number ?? 'N/A' }}</td>
                            <td>{{ $equipment->equipment_name }}</td>
                            <td>{{ $equipment->type }}</td>
                            <td>
                                @if($equipment->trips->last())
                                    {{ number_format($equipment->trips->last()->end_kilometers ?? $equipment->trips->last()->start_kilometers, 0, '.', ',') }} Km
                                @elseif ($equipment->machineryUsages->last())
                                    {{ number_format($equipment->machineryUsages->last()->closing_hours ?? $equipment->machineryUsages->last()->start_hours, 0, '.', ',') }} Hours
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($equipment->status == 'Running')
                                    <span class="badge bg-success">{{ $equipment->status }}</span>
                                @elseif ($equipment->status == 'Under Maintenance')
                                    <span class="badge bg-secondary">{{ $equipment->status }}</span>
                                @elseif ($equipment->status == 'Broken Down')
                                    <span class="badge bg-warning text-dark">{{ $equipment->status }}</span>
                                @elseif ($equipment->status == 'Accident')
                                    <span class="badge bg-danger">{{ $equipment->status }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ $equipment->status ?? 'N/A' }}</span>
                                @endif
                            </td>
                            <td class="d-flex gap-1 align-items-center">
                                <a href="{{ route('equipments.show', $equipment) }}" class="btn btn-info btn-xs"><i class="fas fa-eye"></i> View</a>
                                <a href="{{ route('equipments.edit', $equipment) }}" class="btn btn-warning btn-xs ml-1"><i class="fas fa-edit"></i> Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Modal for Equipment Trip Form -->
    <div class="modal fade" id="logTripModal" tabindex="-1" aria-labelledby="logTripModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#510404">
                    <h5 class="modal-title text-light" id="logTripModalLabel" >Register a Trip</h5>
                    <button type="button" class="btn-close" style="color: #fff;" id="btn_close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <small class="text-danger">Inputs marked with an asterisk (<span class="text-danger"> * </span>) are mandatory</small>

                    <form action="{{ route('trips.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="equipment_id" id="selectedVehicleId">
                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="driver_id" class="form-label">Driver <span class="text-danger">*</span></label>
                                <select name="driver_id" id="driver_id" class="form-control @error('driver_id') is-invalid @enderror" required>
                                    <option value="">Select Driver</option>
                                    @foreach (\App\Models\Employee::whereIn('designation', [
                                        'DRIVER',
                                        'OPERATOR',
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
                                <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                                    value="{{ old('location') }}" placeholder="example: Kasempa, Serenje, Ndola, Solwezi..." required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="departure_date" class="form-label">Departure Date <span class="text-danger">*</span></label>
                                <input type="date" name="departure_date" class="form-control @error('departure_date') is-invalid @enderror"
                                    value="{{ old('departure_date') }}" required>
                                @error('departure_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="return_date" class="form-label">Return Date</label>
                                <input type="date" name="return_date" class="form-control @error('return_date') is-invalid @enderror"
                                    value="{{ old('return_date') }}">
                                @error('return_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="start_kilometers" class="form-label">Start Kilometers <span class="text-danger">*</span></label>
                                <input type="number" name="start_kilometers" id="start_kilometers"
                                    class="form-control @error('start_kilometers') is-invalid @enderror"
                                    value="{{ old('start_kilometers') }}" placeholder="example: 54666" required>
                                @error('start_kilometers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="end_kilometers" class="form-label">Closing Kilometers</label>
                                <input type="number" name="end_kilometers" class="form-control @error('end_kilometers') is-invalid @enderror"
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
                        <div id="fuel-entries">
                            <div class="fuel-entry row mb-3">
                                <div class="col-12 col-md-5">
                                    <label for="litres_added[]" class="form-label">Litres Added <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="fuels[0][litres_added]" class="form-control @error('fuels.0.litres_added') is-invalid @enderror"
                                        value="{{ old('fuels.0.litres_added') }}" placeholder="example: 60" required>
                                    @error('fuels.0.litres_added')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-5">
                                    <label for="refuel_location[]" class="form-label">Refuel Location</label>
                                    <input type="text" name="fuels[0][refuel_location]" class="form-control @error('fuels.0.refuel_location') is-invalid @enderror"
                                        value="{{ old('fuels.0.refuel_location') }}" placeholder="example:Site,Chimwemwe Meru Station, Kalulushi Meru Station">
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
                            <button type="button" class="btn btn-secondary" id="btn_close" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Trip & Fuel</button>
                        </div>
                    </form>
                </div>
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
                        <input type="hidden" name="equipment_id" id="selectedMachineryId">
                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="operator_id" class="form-label">Operator <span class="text-danger">*</span></label>
                                <select name="operator_id" id="operator_id" class="form-control @error('operator_id') is-invalid @enderror" required>
                                    <option value="">Select Operator</option>
                                    @foreach (\App\Models\Employee::whereIn('designation', [
                                        'DRIVER',
                                        'OPERATOR',
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var vehicleSelect = document.getElementById('vehicleSelect');
            var vehicleIdField = document.getElementById('selectedVehicleId');
            var machineryIdField = document.getElementById('selectedMachineryId');
            var tripModalElement = document.getElementById('logTripModal');
            var machineryModalElement = document.getElementById('logMachineryUsageModal');
            var startKilometersField = document.getElementById('start_kilometers');
            var startHoursField = document.getElementById('start_hours');
            var fuelEntriesContainer = document.getElementById('fuel-entries');
            var machineryFuelEntriesContainer = document.getElementById('machinery-fuel-entries');
            var addFuelEntryButton = document.getElementById('add-fuel-entry');
            var addMachineryFuelEntryButton = document.getElementById('add-machinery-fuel-entry');
            var fuelEntryCount = 1;
            var machineryFuelEntryCount = 1;

            if (vehicleSelect && vehicleIdField && machineryIdField && tripModalElement && machineryModalElement && startKilometersField) {
                vehicleSelect.addEventListener('change', function() {
                    if (this.value) {
                        var selectedOption = this.options[this.selectedIndex];
                        var equipmentType = selectedOption.getAttribute('data-equipment-type');

                        if (equipmentType === 'Machinery') {
                            machineryIdField.value = this.value;

                            // Fetch the last Machinery Usage's closing_hours for the selected equipment
                            fetch(`/machinery-usages/last-usage/${this.value}`)
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
                            vehicleIdField.value = this.value;

                            // Fetch the last trip's end_kilometers for the selected equipment
                            fetch(`/trips/last-trip/${this.value}`)
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
                    }
                });

                document.getElementById('btn_close').addEventListener('click', function () {
                    var modalInstance = bootstrap.Modal.getInstance(tripModalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                });

                document.getElementById('btn_close_machinery').addEventListener('click', function () {
                    var modalInstance = bootstrap.Modal.getInstance(machineryModalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                });
            }

            // Add new fuel entry for trip modal
            addFuelEntryButton.addEventListener('click', function() {
                var newEntry = `
                    <div class="fuel-entry row mb-3">
                        <div class="col-12 col-md-5">
                            <label for="litres_added[]" class="form-label">Litres Added <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="fuels[${fuelEntryCount}][litres_added]" class="form-control" placeholder="example: 60" required>
                        </div>
                        <div class="col-12 col-md-5">
                            <label for="refuel_location[]" class="form-label">Refuel Location</label>
                            <input type="text" name="fuels[${fuelEntryCount}][refuel_location]" class="form-control" placeholder="example: Site, Solwezi, Kasempa">
                        </div>
                        <div class="col-12 col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-fuel-entry"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                `;
                fuelEntriesContainer.insertAdjacentHTML('beforeend', newEntry);
                fuelEntryCount++;
                updateRemoveButtons(fuelEntriesContainer);
            });

            // Add new fuel entry for machinery modal
            addMachineryFuelEntryButton.addEventListener('click', function() {
                var newEntry = `
                    <div class="fuel-entry row mb-3">
                        <div class="col-12 col-md-5">
                            <label for="litres_added[]" class="form-label">Litres Added <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="fuels[${machineryFuelEntryCount}][litres_added]" class="form-control" placeholder="example: 60" required>
                        </div>
                        <div class="col-12 col-md-5">
                            <label for="refuel_location[]" class="form-label">Refuel Location</label>
                            <input type="text" name="fuels[${machineryFuelEntryCount}][refuel_location]" class="form-control" placeholder="example: Site, Solwezi, Kasempa">
                        </div>
                        <div class="col-12 col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-fuel-entry"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                `;
                machineryFuelEntriesContainer.insertAdjacentHTML('beforeend', newEntry);
                machineryFuelEntryCount++;
                updateRemoveButtons(machineryFuelEntriesContainer);
            });

            // Remove fuel entry
            function removeFuelEntry(e) {
                if (e.target.classList.contains('remove-fuel-entry') || e.target.parentElement.classList.contains('remove-fuel-entry')) {
                    e.target.closest('.fuel-entry').remove();
                    updateRemoveButtons(e.target.closest('.fuel-entry').parentElement);
                }
            }

            fuelEntriesContainer.addEventListener('click', removeFuelEntry);
            machineryFuelEntriesContainer.addEventListener('click', removeFuelEntry);

            function updateRemoveButtons(container) {
                var entries = container.getElementsByClassName('fuel-entry');
                var removeButtons = container.getElementsByClassName('remove-fuel-entry');
                for (var i = 0; i < removeButtons.length; i++) {
                    removeButtons[i].disabled = (entries.length === 1); // Disable if only one entry remains
                }
            }

            document.addEventListener('input', function() {
                let gross = parseFloat(document.getElementById('gross_weight').value) || 0;
                let tare = parseFloat(document.getElementById('tare_weight').value) || 0;
                document.getElementById('net_weight').value = (gross - tare).toFixed(2);
            });
        });
    </script>
</div>
@endsection
