@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Employee</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <small class="text-danger">Inputs marked with an asterisk (<span class="text-danger"> * </span>) are mandatory</small>

    <form action="{{ route('employees.store') }}" method="POST">
        @csrf

        <!-- Personal Details Section -->
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color:#510404">
                <h5 class="mb-0">Personal Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="first_name">First Name<span class="text-danger">*</span></label>
                            <input type="text" id="first_name" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                   value="{{ old('first_name') }}" placeholder="Example: JOHN" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror"
                                   value="{{ old('middle_name') }}" placeholder="Example: BANDA">
                            @error('middle_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="surname_name">Surname<span class="text-danger">*</span></label>
                            <input type="text" id="surname_name" name="surname_name" class="form-control @error('surname_name') is-invalid @enderror"
                                   value="{{ old('surname_name') }}" placeholder="Example: PHIRI" required>
                            @error('surname_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sex">Sex<span class="text-danger">*</span></label>
                            <select id="sex" name="sex" class="form-control @error('sex') is-invalid @enderror" required>
                                <option value="">Select Sex</option>
                                <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('sex')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth<span class="text-danger">*</span></label>
                            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                                   value="{{ old('date_of_birth') }}" required>
                            @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone_number">Phone Number<span class="text-danger">*</span></label>
                            <input type="text" id="phone_number" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror"
                                   value="{{ old('phone_number') }}" placeholder="Example: 0971234567" required>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" placeholder="Example: johnphiri@gmail.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address">Address<span class="text-danger">*</span></label>
                            <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror"
                                   value="{{ old('address') }}" placeholder="Example: 123 CHIMWEMWE" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="town">Town<span class="text-danger">*</span></label>
                            <input type="text" id="town" name="town" class="form-control @error('town') is-invalid @enderror"
                                   value="{{ old('town') }}" placeholder="Example: KITWE" required>
                            @error('town')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="marital_status">Marital Status<span class="text-danger">*</span></label>
                            <select id="marital_status" name="marital_status" class="form-control @error('marital_status') is-invalid @enderror" required>
                                <option value="">Select Marital Status</option>
                                <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="Widowed" {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                            @error('marital_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nationality">Nationality<span class="text-danger">*</span></label>
                            <input type="text" id="nationality" name="nationality" class="form-control @error('nationality') is-invalid @enderror"
                                   value="{{ old('nationality') }}" placeholder="Example: ZAMBIAN" required>
                            @error('nationality')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Details Section -->
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color:#510404">
                <h5 class="mb-0">Employment Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_of_joining">Date of Joining<span class="text-danger">*</span></label>
                            <input type="date" id="date_of_joining" name="date_of_joining" class="form-control @error('date_of_joining') is-invalid @enderror"
                                   value="{{ old('date_of_joining') }}" required>
                            @error('date_of_joining')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_of_contract">Date of Contract<span class="text-danger">*</span></label>
                            <input type="date" id="date_of_contract" name="date_of_contract" class="form-control @error('date_of_contract') is-invalid @enderror"
                                   value="{{ old('date_of_contract') }}" required>
                            @error('date_of_contract')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_of_termination_of_contract">Termination Date<span class="text-danger">*</span></label>
                            <input type="date" id="date_of_termination_of_contract" name="date_of_termination_of_contract" class="form-control @error('date_of_termination_of_contract') is-invalid @enderror"
                                   value="{{ old('date_of_termination_of_contract') }}" required>
                            @error('date_of_termination_of_contract')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="employee_id">Employee ID<span class="text-danger">*</span></label>
                            <input type="text" id="employee_id" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror"
                                   value="{{ old('employee_id') }}" placeholder="Example: SM004/PT003" required>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nhima_identification_number">NHIMA ID Number</label>
                            <input type="text" id="nhima_identification_number" name="nhima_identification_number" class="form-control @error('nhima_identification_number') is-invalid @enderror"
                                   value="{{ old('nhima_identification_number') }}" placeholder="Example: 112967563110123">
                            @error('nhima_identification_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nrc_or_passport_number">NRC/Passport Number<span class="text-danger">*</span></label>
                            <input type="text" id="nrc_or_passport_number" name="nrc_or_passport_number" class="form-control @error('nrc_or_passport_number') is-invalid @enderror"
                                   value="{{ old('nrc_or_passport_number') }}" placeholder="Example: 123456/78/1" required>
                            @error('nrc_or_passport_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tpin_number">TPIN Number</label>
                            <input type="text" id="tpin_number" name="tpin_number" class="form-control @error('tpin_number') is-invalid @enderror"
                                   value="{{ old('tpin_number') }}" placeholder="Example: 123456789">
                            @error('tpin_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="department">Department<span class="text-danger">*</span></label>
                            <input type="text" id="department" name="department" class="form-control @error('department') is-invalid @enderror"
                                   value="{{ old('department') }}" placeholder="Example: AUTOMOBILE" required>
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="section">Section<span class="text-danger">*</span></label>
                            <input type="text" id="section" name="section" class="form-control @error('section') is-invalid @enderror"
                                   value="{{ old('section') }}" placeholder="Example: GARAGE" required>
                            @error('section')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="designation">Designation<span class="text-danger">*</span></label>
                            <input type="text" id="designation" name="designation" class="form-control @error('designation') is-invalid @enderror"
                                   value="{{ old('designation') }}" placeholder="Example: MECHANICS ASSISTANT" required>
                            @error('designation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="grade">Grade</label>
                            <input type="text" id="grade" name="grade" class="form-control @error('grade') is-invalid @enderror"
                                   value="{{ old('grade') }}">
                            @error('grade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status<span class="text-danger">*</span></label>
                            <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="">Select Status</option>
                                <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="On Leave" {{ old('status') == 'On Leave' ? 'selected' : '' }}>On Leave</option>
                                <option value="Terminated" {{ old('status') == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Salary Details Section -->
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color:#510404">
                <h5 class="mb-0">Salary Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="basic_salary">Basic Salary<span class="text-danger">*</span></label>
                            <input type="number" id="basic_salary" name="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror"
                                   value="{{ old('basic_salary') }}" placeholder="Example: 5000" required>
                            @error('basic_salary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="other_allowances">Other Allowances</label>
                            <input type="number" id="other_allowances" name="other_allowances" class="form-control @error('other_allowances') is-invalid @enderror"
                                   value="{{ old('other_allowances') }}" placeholder="Example: 200">
                            @error('other_allowances')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Details Section -->
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color:#510404">
                <h5 class="mb-0">Payment Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_method">Payment Method<span class="text-danger">*</span></label>
                            <select id="payment_method" name="payment_method" class="form-control @error('payment_method') is-invalid @enderror" required>
                                <option value="">Select Payment Method</option>
                                <option value="Bank" {{ old('payment_method') == 'Bank' ? 'selected' : '' }}>Bank</option>
                                <option value="MTN Mobile Money" {{ old('payment_method') == 'MTN Mobile Money' ? 'selected' : '' }}>MTN Mobile Money</option>
                                <option value="Airtel Mobile Money" {{ old('payment_method') == 'Airtel Mobile Money' ? 'selected' : '' }}>Airtel Mobile Money</option>
                                <option value="Zamtel Mobile Money" {{ old('payment_method') == 'Zamtel Mobile Money' ? 'selected' : '' }}>Zamtel Mobile Money</option>
                                <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="social_security_number">Social Security Number</label>
                            <input type="text" id="social_security_number" name="social_security_number" class="form-control @error('social_security_number') is-invalid @enderror"
                                   value="{{ old('social_security_number') }}" placeholder="Example: 311942746">
                            @error('social_security_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Details Section -->
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color:#510404">
                <h5 class="mb-0">Bank Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="account_name">Account Name</label>
                            <input type="text" id="account_name" name="account_name" class="form-control @error('account_name') is-invalid @enderror"
                                   value="{{ old('account_name') }}" placeholder="Example: John Phiri">
                            @error('account_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ifsc_code">IFSC Code</label>
                            <input type="text" id="ifsc_code" name="ifsc_code" class="form-control @error('ifsc_code') is-invalid @enderror"
                                   value="{{ old('ifsc_code') }}" placeholder="Example: IFSC001">
                            @error('ifsc_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bank_name">Bank Name</label>
                            <input type="text" id="bank_name" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror"
                                   value="{{ old('bank_name') }}" placeholder="Example: Indo Zambia Bank">
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="branch_name">Branch Name</label>
                            <input type="text" id="branch_name" name="branch_name" class="form-control @error('branch_name') is-invalid @enderror"
                                   value="{{ old('branch_name') }}" placeholder="Example: Kitwe Branch">
                            @error('branch_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bank_address">Bank Address</label>
                            <input type="text" id="bank_address" name="bank_address" class="form-control @error('bank_address') is-invalid @enderror"
                                   value="{{ old('bank_address') }}" placeholder="Example: 123 Town Center, Kitwe">
                            @error('bank_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bank_telephone_number">Bank Telephone Number</label>
                            <input type="text" id="bank_telephone_number" name="bank_telephone_number" class="form-control @error('bank_telephone_number') is-invalid @enderror"
                                   value="{{ old('bank_telephone_number') }}" placeholder="Example: 0211123456">
                            @error('bank_telephone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bank_account_number">Bank Account Number</label>
                            <input type="text" id="bank_account_number" name="bank_account_number" class="form-control @error('bank_account_number') is-invalid @enderror"
                                   value="{{ old('bank_account_number') }}" placeholder="Example: 1234567890">
                            @error('bank_account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- References Section -->
        <div class="card mb-4">
            <div class="card-header text-white" style="background-color:#510404">
                <h5 class="mb-0">References (Optional)</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="references">References</label>
                            <div id="references-container">
                                @if (old('references'))
                                    @foreach (old('references') as $index => $reference)
                                        @if (!empty($reference['name']) || !empty($reference['phone_number']))
                                            <div class="reference-group mb-3">
                                                <input type="text" name="references[{{ $index }}][name]" class="form-control mb-2 @error("references.{$index}.name") is-invalid @enderror"
                                                       placeholder="Reference Name" value="{{ $reference['name'] }}">
                                                @error("references.{$index}.name")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <input type="text" name="references[{{ $index }}][phone_number]" class="form-control @error("references.{$index}.phone_number") is-invalid @enderror"
                                                       placeholder="Reference Phone Number" value="{{ $reference['phone_number'] }}">
                                                @error("references.{$index}.phone_number")
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <button type="button" class="btn btn-danger btn-sm mt-2 remove-reference">Remove</button>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" id="add-reference" class="btn btn-secondary mt-2">Add Reference</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-3">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Add Employee</button>
            <a href="{{ route('employees.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
        </div>
    </form>
</div>

<!-- JavaScript to Add/Remove Reference Fields -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('references-container');
        let index = container.children.length;

        // Add new reference field
        document.getElementById('add-reference').addEventListener('click', function () {
            const div = document.createElement('div');
            div.classList.add('reference-group', 'mb-3');
            div.innerHTML = `
                <input type="text" name="references[${index}][name]" class="form-control mb-2" placeholder="Reference Name">
                <input type="text" name="references[${index}][phone_number]" class="form-control" placeholder="Reference Phone Number">
                <button type="button" class="btn btn-danger btn-sm mt-2 remove-reference">Remove</button>
            `;
            container.appendChild(div);
            index++;
            updateRemoveButtons();
        });

        // Remove reference field
        container.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-reference')) {
                e.target.closest('.reference-group').remove();
                updateRemoveButtons();
            }
        });

        // Enable/disable remove buttons
        function updateRemoveButtons() {
            const groups = container.getElementsByClassName('reference-group');
            const removeButtons = container.getElementsByClassName('remove-reference');
            for (let i = 0; i < removeButtons.length; i++) {
                removeButtons[i].disabled = groups.length === 0; // No disable logic since we allow all to be removed
            }
        }

        // Initial call to set button state
        updateRemoveButtons();
    });
</script>
@endsection
