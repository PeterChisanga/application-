@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Machinery Usage</h2>

    @if (session('success'))
        <div class="alert alert-success" id="successMessage">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" id="successMessage">{{ session('error') }}</div>
    @endif

    <form id="updateMachineryUsageForm" action="{{ route('machinery_usages.update') }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $usage->id }}">

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror"
                    value="{{ old('date', $usage->date->format('Y-m-d')) }}" required>
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="operator_id" class="form-label">Operator</label>
                <select name="operator_id" id="operator_id" class="form-control @error('operator_id') is-invalid @enderror">
                    <option value="">Select Operator</option>
                    @foreach ($operators as $operator)
                        <option value="{{ $operator->id }}"
                            {{ old('operator_id', $usage->operator_id) == $operator->id ? 'selected' : '' }}>
                            {{ $operator->employee_full_name }} ({{ $operator->designation }})
                        </option>
                    @endforeach
                </select>
                @error('operator_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror"
                    value="{{ old('location', $usage->location) }}" placeholder="e.g., Site">
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="start_hours" class="form-label">Start Hours</label>
                <input type="number" step="0.01" name="start_hours" id="start_hours" class="form-control @error('start_hours') is-invalid @enderror"
                    value="{{ old('start_hours', $usage->start_hours) }}" placeholder="e.g., 100.50">
                @error('start_hours')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="closing_hours" class="form-label">Closing Hours</label>
                <input type="number" step="0.01" name="closing_hours" id="closing_hours" class="form-control @error('closing_hours') is-invalid @enderror"
                    value="{{ old('closing_hours', $usage->closing_hours) }}" placeholder="e.g., 110.50">
                @error('closing_hours')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <h4>Fuel Entries</h4>
            <div id="fuel-entries-edit-usage">
                @forelse ($usage->fuels as $index => $fuel)
                    <div class="fuel-entry row mb-3" data-fuel-id="{{ $fuel->id }}">
                        <div class="col-12 col-md-4">
                            <label for="litres_added_{{ $index }}" class="form-label">Litres Added <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="fuels[{{ $index }}][litres_added]" id="litres_added_{{ $index }}"
                                class="form-control @error('fuels.' . $index . '.litres_added') is-invalid @enderror"
                                value="{{ old('fuels.' . $index . '.litres_added', $fuel->litres_added) }}" placeholder="e.g., 60" required>
                            @error('fuels.' . $index . '.litres_added')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="cost_{{ $index }}" class="form-label">Cost per Litre (ZMW)</label>
                            <input type="number" step="0.01" name="fuels[{{ $index }}][cost]" id="cost_{{ $index }}"
                                class="form-control @error('fuels.' . $index . '.cost') is-invalid @enderror"
                                value="{{ old('fuels.' . $index . '.cost', $fuel->cost) }}" placeholder="e.g., 30.23">
                            @error('fuels.' . $index . '.cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="refuel_location_{{ $index }}" class="form-label">Refuel Location</label>
                            <input type="text" name="fuels[{{ $index }}][refuel_location]" id="refuel_location_{{ $index }}"
                                class="form-control @error('fuels.' . $index . '.refuel_location') is-invalid @enderror"
                                value="{{ old('fuels.' . $index . '.refuel_location', $fuel->refuel_location) }}"
                                placeholder="e.g., Site, Meru Station">
                            @error('fuels.' . $index . '.refuel_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-2 d-flex align-items-end">
                            <input type="hidden" name="fuels[{{ $index }}][id]" value="{{ $fuel->id }}">
                            <button type="button" class="btn btn-danger remove-fuel-entry" {{ $loop->first ? 'disabled' : '' }}>
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="fuel-entry row mb-3">
                        <div class="col-12 col-md-4">
                            <label for="litres_added_0" class="form-label">Litres Added <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="fuels[0][litres_added]" id="litres_added_0"
                                class="form-control @error('fuels.0.litres_added') is-invalid @enderror"
                                value="{{ old('fuels.0.litres_added') }}" placeholder="e.g., 60" required>
                            @error('fuels.0.litres_added')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="cost_0" class="form-label">Cost per Litre (ZMW)</label>
                            <input type="number" step="0.01" name="fuels[0][cost]" id="cost_0"
                                class="form-control @error('fuels.0.cost') is-invalid @enderror"
                                value="{{ old('fuels.0.cost') }}" placeholder="e.g., 30.23">
                            @error('fuels.0.cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="refuel_location_0" class="form-label">Refuel Location</label>
                            <input type="text" name="fuels[0][refuel_location]" id="refuel_location_0"
                                class="form-control @error('fuels.0.refuel_location') is-invalid @enderror"
                                value="{{ old('fuels.0.refuel_location') }}" placeholder="e.g., Site, Meru Station">
                            @error('fuels.0.refuel_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger remove-fuel-entry" disabled>
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>
            <button type="button" id="add-fuel-entry-edit-usage" class="btn btn-primary mb-3">Add Fuel Entry</button>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i>Update Machinery Usage</button>
            <a href="{{ route('equipments.show', $usage->equipment_id) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<!-- JavaScript for AJAX and dynamic fuel entries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let fuelEntryCount = {{ $usage->fuels->count() ?: 1 }};

        $('#add-fuel-entry-edit-usage').on('click', function() {
            var newEntry = `
                <div class="fuel-entry row mb-3">
                    <div class="col-12 col-md-4">
                        <label for="litres_added_${fuelEntryCount}" class="form-label">Litres Added <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="fuels[${fuelEntryCount}][litres_added]" id="litres_added_${fuelEntryCount}"
                            class="form-control" placeholder="e.g., 60" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="cost_${fuelEntryCount}" class="form-label">Cost per Litre (ZMW)</label>
                        <input type="number" step="0.01" name="fuels[${fuelEntryCount}][cost]" id="cost_${fuelEntryCount}"
                            class="form-control" placeholder="e.g., 30.23">
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="refuel_location_${fuelEntryCount}" class="form-label">Refuel Location</label>
                        <input type="text" name="fuels[${fuelEntryCount}][refuel_location]" id="refuel_location_${fuelEntryCount}"
                            class="form-control" placeholder="e.g., Site, Meru Station">
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-fuel-entry">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#fuel-entries-edit-usage').append(newEntry);
            fuelEntryCount++;
            updateRemoveButtons();
        });

        $('#fuel-entries-edit-usage').on('click', '.remove-fuel-entry', function() {
            $(this).closest('.fuel-entry').remove();
            updateRemoveButtons();
        });

        function updateRemoveButtons() {
            var entries = $('#fuel-entries-edit-usage .fuel-entry');
            var removeButtons = $('#fuel-entries-edit-usage .remove-fuel-entry');
            removeButtons.prop('disabled', entries.length === 1);
        }

        updateRemoveButtons();
    });
</script>
@endsection

