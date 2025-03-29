<div class="modal-body">
    <form id="updateTripForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="equipment_id" id="updateEquipmentId">
        <input type="hidden" name="trip_id" id="updateTripId">

        <div class="row mb-3">
            <div class="col-12 col-md-6">
                <label for="driver_id" class="form-label">Driver <span class="text-danger">*</span></label>
                <select name="driver_id" id="driver_id" class="form-control @error('driver_id') is-invalid @enderror" required>
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
                <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror"
                    value="{{ old('location') }}" placeholder="example: Kasempa, Serenje, Ndola, Solwezi..." required>
                @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12 col-md-6">
                <label for="departure_date" class="form-label">Departure Date <span class="text-danger">*</span></label>
                <input type="date" name="departure_date" id="departure_date" class="form-control @error('departure_date') is-invalid @enderror"
                    value="{{ old('departure_date') }}" required>
                @error('departure_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-md-6">
                <label for="return_date" class="form-label">Return Date</label>
                <input type="date" name="return_date" id="return_date" class="form-control @error('return_date') is-invalid @enderror"
                    value="{{ old('return_date') }}">
                @error('return_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12 col-md-6">
                <label for="start_kilometers" class="form-label">Start Kilometers <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="start_kilometers" id="start_kilometers"
                    class="form-control @error('start_kilometers') is-invalid @enderror"
                    value="{{ old('start_kilometers') }}" placeholder="example: 54666" required>
                @error('start_kilometers')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-md-6">
                <label for="end_kilometers" class="form-label">Closing Kilometers</label>
                <input type="number" step="0.01" name="end_kilometers" id="end_kilometers" class="form-control @error('end_kilometers') is-invalid @enderror"
                    value="{{ old('end_kilometers') }}" placeholder="example: 54777">
                @error('end_kilometers')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12 col-md-6">
                <label for="material_delivered" class="form-label">Material Delivered</label>
                <input type="text" name="material_delivered" id="material_delivered" class="form-control @error('material_delivered') is-invalid @enderror"
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
                <!-- Empty column for balance -->
            </div>
        </div>

        <!-- Trip Expenses Section -->
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

        <h4 class="mt-4">Fuel Information</h4>
        <div id="fuel-entries-edit-trip">
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
        <button type="button" class="btn btn-primary mb-3" id="add-fuel-entry-edit-trip"><i class="fas fa-plus"></i> Add Another Fuel Entry</button>

        <div class="modal-footer">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update Trip & Fuel</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
        </div>
    </form>
</div>
