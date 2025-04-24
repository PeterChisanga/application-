@extends('layouts.app')

@section('title', 'Employee Details')

@section('content')
<div class="container ">
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

    <h2 class="mb-4">
        Employee Details: {{ $employee->employee_full_name }} (ID: {{ $employee->employee_id }})
    </h2>

    <div class="d-flex flex-column flex-md-row gap-2 mb-4">
        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm mr-2  btn-sm w-30 mt-2 mt-md-0 mb-2 mb-md-0">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('employees.print_employee_information', $employee->id) }}" class="btn btn-success btn-sm w-30 mt-2 mt-md-0 mb-2 mb-md-0">
            <i class="fas fa-print"></i> Print Employee Infomation
        </a>
    </div>

    <!-- Tabs for Employee Details -->
    <ul class="nav nav-tabs" id="employeeTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">Details</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="payslips-tab" data-bs-toggle="tab" data-bs-target="#payslips" type="button" role="tab" aria-controls="payslips" aria-selected="false">Payslip</button>
        </li>
    </ul>

    <div class="tab-content" id="employeeTabsContent">
        <!-- Employee Details Tab -->
        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
            <div class="card mt-3">
                <div class="card-header" style="background-color:#510404; color: #fff;">
                    <h5 class="mb-0">Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>First Name:</strong> {{ $employee->first_name }}</p>
                            <p><strong>Middle Name:</strong> {{ $employee->middle_name ?? 'N/A' }}</p>
                            <p><strong>Surname:</strong> {{ $employee->surname_name }}</p>
                            <p><strong>Full Name:</strong> {{ $employee->employee_full_name }}</p>
                            <p><strong>Sex:</strong> {{ $employee->sex }}</p>
                            <p><strong>Date of Birth:</strong> {{ $employee->date_of_birth ? $employee->date_of_birth->format('Y-m-d') : 'N/A' }}</p>
                            <p><strong>Marital Status:</strong> {{ $employee->marital_status }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Phone Number:</strong> {{ $employee->phone_number }}</p>
                            <p><strong>Email:</strong> {{ $employee->email ?? 'N/A' }}</p>
                            <p><strong>Address:</strong> {{ $employee->address }}</p>
                            <p><strong>Town:</strong> {{ $employee->town }}</p>
                            <p><strong>Nationality:</strong> {{ $employee->nationality }}</p>
                            <p>
                                @if ($employee->status == 'Active')
                                    <div class="btn btn-sm" style="background-color: #28a745; color: white; border-radius: 4px; padding: 0.25rem 0.5rem;"><strong>Status: </strong>{{ $employee->status }}</div>
                                @elseif ($employee->status == 'Inactive')
                                    <div class="btn btn-sm" style="background-color: #6c757d; color: white; border-radius: 4px; padding: 0.25rem 0.5rem;"><strong>Status: </strong>{{ $employee->status }}</div>
                                @elseif ($employee->status == 'On Leave')
                                    <div class="btn btn-sm" style="background-color: #ffc107; color: white; border-radius: 4px; padding: 0.25rem 0.5rem;"><strong>Status: </strong>{{ $employee->status }}</div>
                                @elseif ($employee->status == 'Terminated')
                                    <div class="btn btn-sm" style="background-color: #dc3545; color: white; border-radius: 4px; padding: 0.25rem 0.5rem;"><strong>Status: </strong>{{ $employee->status }}</div>
                                @else
                                    <div class="btn btn-sm" style="background-color: #6c757d; color: white; border-radius: 4px; padding: 0.25rem 0.5rem;"><strong>Status: </strong>{{ $employee->status ?? 'N/A' }}</div>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header" style="background-color:#510404; color: #fff;">
                    <h5 class="mb-0">Employment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Date of Joining:</strong> {{ $employee->date_of_joining->format('Y-m-d') }}</p>
                            <p><strong>Date of Contract:</strong> {{ $employee->date_of_contract ? $employee->date_of_contract->format('Y-m-d') : 'N/A' }}</p>
                            <p><strong>Date of Termination:</strong> {{ $employee->date_of_termination_of_contract ? $employee->date_of_termination_of_contract->format('Y-m-d') : 'N/A' }}</p>
                            <p><strong>Employee ID:</strong> {{ $employee->employee_id }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>NHIMA ID Number:</strong> {{ $employee->nhima_identification_number ?? 'N/A' }}</p>
                            <p><strong>TPIN Number:</strong> {{ $employee->tpin_number ?? 'N/A' }}</p>
                            <p><strong>NRC/Passport Number:</strong> {{ $employee->nrc_or_passport_number }}</p>
                            <p><strong>Social Security Number:</strong> {{ $employee->social_security_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p><strong>Designation:</strong> {{ $employee->designation }}</p>
                            <p><strong>Department:</strong> {{ $employee->department }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Section:</strong> {{ $employee->section }}</p>
                            <p><strong>Grade:</strong> {{ $employee->grade ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header" style="background-color:#510404; color: #fff;">
                    <h5 class="mb-0">Compensation Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Basic Salary:</strong> {{ number_format($employee->basic_salary, 2) }} ZMW</p>
                            <p><strong>Housing Allowance:</strong> {{ number_format($employee->housing_allowance, 2) }} ZMW</p>
                            <p><strong>Transport Allowance:</strong> {{ number_format($employee->transport_allowance, 2) }} ZMW</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Food Allowance:</strong> {{ number_format($employee->food_allowance, 2) }} ZMW</p>
                            <p><strong>Other Allowances:</strong> {{ number_format($employee->other_allowances ?? 0, 2) }} ZMW</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header" style="background-color:#510404; color: #fff;">
                    <h5 class="mb-0">Banking Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Payment Method:</strong> {{ $employee->payment_method }}</p>
                            <p><strong>Account Name:</strong> {{ $employee->account_name ?? 'N/A' }}</p>
                            <p><strong>Bank Name:</strong> {{ $employee->bank_name ?? 'N/A' }}</p>
                            <p><strong>Branch Name:</strong> {{ $employee->branch_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Bank Account Number:</strong> {{ $employee->bank_account_number ?? 'N/A' }}</p>
                            <p><strong>IFSC Code:</strong> {{ $employee->ifsc_code ?? 'N/A' }}</p>
                            <p><strong>Bank Address:</strong> {{ $employee->bank_address ?? 'N/A' }}</p>
                            <p><strong>Bank Telephone Number:</strong> {{ $employee->bank_telephone_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if ($employee->references && is_array($employee->references))
                <div class="card mt-3">
                    <div class="card-header" style="background-color:#510404; color: #fff;">
                        <h5 class="mb-0">References</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($employee->references as $reference)
                                <div class="col-md-6 mb-3">
                                    <p><strong>Name:</strong> {{ $reference['name'] }}</p>
                                    <p><strong>Phone Number:</strong> {{ $reference['phone_number'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Payslips Tab -->
        <div class="tab-pane fade" id="payslips" role="tabpanel" aria-labelledby="payslips-tab">
            <div class="card mt-3">
                <div class="card-header" style="background-color:#510404; color: #fff;">
                    <h5 class="mb-0">Payslip</h5>
                </div>

                <button type="button" class="btn btn-success btn-sm mt-2 me-2" style="width: 30%;" data-bs-toggle="modal" data-bs-target="#payslipModal" data-employee-id="{{ $employee->id }}">
                    <i class="fas fa-print"></i> Print Payslip
                </button>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Basic Salary:</strong> {{ number_format($employee->basic_salary, 2) }} ZMW</p>
                            <p><strong>Housing Allowance:</strong> {{ number_format($employee->housing_allowance, 2) }} ZMW</p>
                            <p><strong>Transport Allowance:</strong> {{ number_format($employee->transport_allowance, 2) }} ZMW</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Food Allowance:</strong> {{ number_format($employee->food_allowance, 2) }} ZMW</p>
                            <p><strong>Other Allowances:</strong> {{ number_format($employee->other_allowances ?? 0, 2) }} ZMW</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    @if ($employee->payslips->isEmpty())
                        <div class="alert alert-warning">No payslips available for this employee.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Gross Earnings</th>
                                        <th>Total Deductions</th>
                                        <th>Net Pay</th>
                                        <th>NAPSA Contribution</th>
                                        <th>NHIMA</th>
                                        <th>Tax Deduction (ZRA)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employee->payslips as $payslip)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ number_format($payslip->gross_earnings, 2) }} ZMW</td>
                                            <td>{{ number_format($payslip->total_deductions, 2) }} ZMW</td>
                                            <td>{{ number_format($payslip->net_pay, 2) }} ZMW</td>
                                            <td>{{ number_format($payslip->napsa_contribution, 2) }} ZMW</td>
                                            <td>{{ number_format($payslip->nhima, 2) }} ZMW</td>
                                            <td>{{ number_format($payslip->tax_deduction, 2) }} ZMW</td>
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

    <!-- Payslip Modal -->
    <div class="modal fade" id="payslipModal" tabindex="-1" aria-labelledby="payslipModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payslipModalLabel">Edit and Confirm Payslip</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="payslipForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <h6>Earnings</h6>
                                <div class="mb-2">
                                    <label for="basic_salary" class="form-label">Basic Salary (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="basic_salary" name="basic_salary" required>
                                </div>
                                <div class="mb-2">
                                    <label for="days_worked" class="form-label">Days Worked</label>
                                    <input type="number" step="1" class="form-control" id="days_worked" name="days_worked" required>
                                </div>
                                <div class="mb-2">
                                    <label for="leave_days" class="form-label">Leave Days</label>
                                    <input type="number" step="1" class="form-control" id="leave_days" name="leave_days" required>
                                </div>
                                <div class="mb-2">
                                    <label for="leave_value" class="form-label">Leave Value (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="leave_value" name="leave_value">
                                </div>
                                <div class="mb-2">
                                    <label for="housing_allowance" class="form-label">Housing Allowance (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="housing_allowance" name="housing_allowance" required>
                                </div>
                                <div class="mb-2">
                                    <label for="overtime_hours" class="form-label">Overtime Hours</label>
                                    <input type="number" step="1" class="form-control" id="overtime_hours" name="overtime_hours" required>
                                </div>
                                <div class="mb-2">
                                    <label for="overtime_pay" class="form-label">Overtime Pay (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="overtime_pay" name="overtime_pay" required>
                                </div>
                                <div class="mb-2">
                                    <label for="sundays_worked" class="form-label">Sundays Worked</label>
                                    <input type="number" step="1" class="form-control" id="sundays_worked" name="sundays_worked" required>
                                </div>
                                <div class="mb-2">
                                    <label for="sundays_pay" class="form-label">Sundays Pay (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="sundays_pay" name="sundays_pay" required>
                                </div>
                                <div class="mb-2">
                                    <label for="forced_leave" class="form-label">Forced Leave (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="forced_leave" name="forced_leave" required>
                                </div>
                                <div class="mb-2">
                                    <label for="transport_allowance" class="form-label">Transport Allowance (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="transport_allowance" name="transport_allowance" required>
                                </div>
                                <div class="mb-2">
                                    <label for="lunch_allowance" class="form-label">Lunch Allowance (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="lunch_allowance" name="lunch_allowance" required>
                                </div>
                                <div class="mb-2">
                                    <label for="other_allowances" class="form-label">Other Allowances (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="other_allowances" name="other_allowances">
                                </div>
                                <div class="mb-2">
                                    <label for="total_earnings" class="form-label">Total Earnings (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="total_earnings" name="total_earnings" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6>Deductions</h6>
                                <div class="mb-2">
                                    <label for="tax_rate" class="form-label">Tax Rate (ZMW)</label>
                                    <input type="number" step="0.01" class="form-control" id="tax_rate" name="tax_rate" required>
                                </div>
                                <div class="mb-2">
                                    <label for="napsa" class="form-label">NAPSA (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="napsa" name="napsa" required>
                                </div>
                                <div class="mb-2">
                                    <label for="nhima" class="form-label">NHIMA (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="nhima" name="nhima" required>
                                </div>
                                <div class="mb-2">
                                    <label for="advance" class="form-label">Advance (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="advance" name="advance" required>
                                </div>
                                <div class="mb-2">
                                    <label for="umuz_fee" class="form-label">UMUZ Fee (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="umuz_fee" name="umuz_fee" required>
                                </div>
                                <div class="mb-2">
                                    <label for="double_deducted" class="form-label">Double Deducted (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="double_deducted" name="double_deducted" required>
                                </div>
                                <div class="mb-2">
                                    <label for="total_deductions" class="form-label">Total Deductions (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="total_deductions" name="total_deductions" required>
                                </div>
                                <div class="mb-2">
                                    <label for="net_pay" class="form-label">Net Pay (ZMW)</label>
                                    <input type="number" step="1" class="form-control" id="net_pay" name="net_pay" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6>Year-to-Date Totals</h6>
                                <div class="mb-2">
                                    <label for="tax_paid_ytd" class="form-label">Tax Paid YTD (ZMW)</label>
                                    <input type="number" step="0.01" class="form-control" id="tax_paid_ytd" name="tax_paid_ytd" required>
                                </div>
                                <div class="mb-2">
                                    <label for="taxable_earnings_ytd" class="form-label">Taxable Earnings YTD (ZMW)</label>
                                    <input type="number" step="0.01" class="form-control" id="taxable_earnings_ytd" name="taxable_earnings_ytd" required>
                                </div>
                                <div class="mb-2">
                                    <label for="annual_leave_due" class="form-label">Annual Leave Due</label>
                                    <input type="number" step="0.01" class="form-control" id="annual_leave_due" name="annual_leave_due" required>
                                </div>
                                <div class="mb-2">
                                    <label for="leave_value_ytd" class="form-label">Leave Value YTD (ZMW)</label>
                                    <input type="number" step="0.01" class="form-control" id="leave_value_ytd" name="leave_value_ytd" required>
                                </div>
                                <h6>Additional Information</h6>
                                <div class="mb-2">
                                    <label for="pay_period" class="form-label">Pay Period</label>
                                    <input type="date" class="form-control" id="pay_period" name="pay_period" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmPayslip" data-bs-target="#payslipModal" data-employee-id="{{ $employee->id }}">Confirm and Generate Payslip</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Modal -->
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const payslipModal = document.getElementById('payslipModal');
        payslipModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const employeeId = button.getAttribute('data-employee-id');
            const url = '{{ route("employees.generatePayslip", ":id") }}'.replace(':id', employeeId);

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('basic_salary').value = data.basic_salary;
                document.getElementById('days_worked').value = data.days_worked;
                document.getElementById('leave_days').value = data.leave_days;
                document.getElementById('leave_value').value = data.leave_value || 0;
                document.getElementById('housing_allowance').value = data.housing_allowance;
                document.getElementById('overtime_hours').value = data.overtime_hours;
                document.getElementById('overtime_pay').value = data.overtime_pay;
                document.getElementById('sundays_worked').value = data.sundays_worked;
                document.getElementById('sundays_pay').value = data.sundays_pay;
                document.getElementById('transport_allowance').value = data.transport_allowance;
                document.getElementById('lunch_allowance').value = data.lunch_allowance;
                document.getElementById('other_allowances').value = data.other_allowances || '';
                document.getElementById('total_earnings').value = data.total_earnings;
                document.getElementById('tax_rate').value = data.tax_rate;
                document.getElementById('napsa').value = data.napsa;
                document.getElementById('nhima').value = data.nhima;
                document.getElementById('advance').value = data.advance;
                document.getElementById('umuz_fee').value = data.umuz_fee;
                document.getElementById('double_deducted').value = data.double_deducted;
                document.getElementById('forced_leave').value = data.forced_leave;
                document.getElementById('total_deductions').value = data.total_deductions;
                document.getElementById('net_pay').value = data.net_pay;
                document.getElementById('tax_paid_ytd').value = data.tax_paid_ytd;
                document.getElementById('taxable_earnings_ytd').value = data.taxable_earnings_ytd;
                document.getElementById('annual_leave_due').value = data.annual_leave_due;
                document.getElementById('leave_value_ytd').value = data.leave_value_ytd;
                document.getElementById('pay_period').value = data.pay_period;
            })
            .catch(error => {
                console.error('Error fetching payslip data:', error);
                alert('Failed to load payslip data.');
            });
        });

        document.getElementById('confirmPayslip').addEventListener('click', function () {
            const form = document.getElementById('payslipForm');
            const formData = new FormData(form);
            const employeeId = payslipModal.querySelector('button[data-employee-id]').getAttribute('data-employee-id');
            const url = '{{ route("employees.generatePayslip", ":id") }}'.replace(':id', employeeId);

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/pdf'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to generate PDF');
                return response.blob();
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = '{{ $employee->employee_full_name }}_payslip.pdf';
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
                bootstrap.Modal.getInstance(payslipModal).hide();
            })
            .catch(error => {
                console.error('Error generating PDF:', error);
                alert('Failed to generate payslip PDF.');
            });
        });
    });
</script> --}}

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const payslipModal = document.getElementById('payslipModal');
        const form = document.getElementById('payslipForm');

        // Function to calculate payslip values
        function calculatePayslip() {
            const basicSalary = parseFloat(document.getElementById('basic_salary').value) || 0;
            const sundaysWorked = parseFloat(document.getElementById('sundays_worked').value) || 0;
            const overtimeHours = parseFloat(document.getElementById('overtime_hours').value) || 0;
            const annualLeaveDue = parseFloat(document.getElementById('annual_leave_due').value) || 0;
            const housingAllowance = basicSalary * 0.3; // 30% of basic salary
            const sundaysPay = (basicSalary / 26) * sundaysWorked * 2; // Double daily rate per Sunday
            const overtimePay = ((basicSalary / 26) / 8) * overtimeHours * 1.5; // 1.5x hourly rate per overtime hour
            const transportAllowance = parseFloat(document.getElementById('transport_allowance').value) || 0;
            const lunchAllowance = parseFloat(document.getElementById('lunch_allowance').value) || 0;
            const otherAllowances = parseFloat(document.getElementById('other_allowances').value) || 0;
            const forcedLeave = parseFloat(document.getElementById('forced_leave').value) || 0;

            const leaveValueYtd = (basicSalary + housingAllowance + transportAllowance + lunchAllowance + otherAllowances) / 26 * annualLeaveDue;

            const totalEarnings = basicSalary + housingAllowance + transportAllowance + lunchAllowance +
                otherAllowances + overtimePay + sundaysPay + forcedLeave;

            const napsa = totalEarnings * 0.05;
            const nhima = basicSalary * 0.01;

            let taxRate = 0;
            if (totalEarnings > 9200) {
                taxRate = (5100 * 0) + (2000 * 0.2) + (2100 * 0.3) + ((totalEarnings - 9200) * 0.37);
            } else if (totalEarnings > 7100) {
                taxRate = (5100 * 0) + (2000 * 0.2) + ((totalEarnings - 7100) * 0.3);
            } else if (totalEarnings > 5100) {
                taxRate = (5100 * 0) + ((totalEarnings - 5100) * 0.2);
            }

            const advance = parseFloat(document.getElementById('advance').value) || 0;
            const umuzFee = parseFloat(document.getElementById('umuz_fee').value) || 0;
            const doubleDeducted = parseFloat(document.getElementById('double_deducted').value) || 0;

            const totalDeductions = taxRate + napsa + nhima + advance + umuzFee + doubleDeducted;
            const netPay = totalEarnings - totalDeductions;

            document.getElementById('housing_allowance').value = housingAllowance.toFixed(2);
            document.getElementById('sundays_pay').value = sundaysPay.toFixed(2);
            document.getElementById('overtime_pay').value = overtimePay.toFixed(2);
            document.getElementById('leave_value_ytd').value = leaveValueYtd.toFixed(2);
            document.getElementById('total_earnings').value = totalEarnings.toFixed(2);
            document.getElementById('napsa').value = napsa.toFixed(2);
            document.getElementById('nhima').value = nhima.toFixed(2);
            document.getElementById('tax_rate').value = taxRate.toFixed(2);
            document.getElementById('total_deductions').value = totalDeductions.toFixed(2);
            document.getElementById('net_pay').value = netPay.toFixed(2);
        }

        // event listeners
        const inputFields = [
            'basic_salary', 'sundays_worked', 'overtime_hours', 'transport_allowance',
            'lunch_allowance', 'other_allowances', 'forced_leave', 'advance',
            'umuz_fee', 'double_deducted', 'annual_leave_due'
        ];
        inputFields.forEach(id => {
            document.getElementById(id).addEventListener('input', calculatePayslip);
        });

        // Fetch initial payslip data
        payslipModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const employeeId = button.getAttribute('data-employee-id');
            const url = '{{ route("employees.generatePayslip", ":id") }}'.replace(':id', employeeId);

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('basic_salary').value = data.basic_salary || 0.0;
                document.getElementById('housing_allowance').value = data.housing_allowance || 0.0;
                document.getElementById('days_worked').value = data.days_worked || 0.0;
                document.getElementById('leave_days').value = data.leave_days || 0.0;
                document.getElementById('leave_value').value = data.leave_value || 0.0;
                document.getElementById('overtime_hours').value = data.overtime_hours || 0.0;
                document.getElementById('overtime_pay').value = data.overtime_pay || 0.0;
                document.getElementById('sundays_worked').value = data.sundays_worked || 0.0;
                document.getElementById('sundays_pay').value = data.sundays_pay || 0.0;
                document.getElementById('forced_leave').value = data.forced_leave || 0.0;
                document.getElementById('transport_allowance').value = data.transport_allowance || 0.0;
                document.getElementById('lunch_allowance').value = data.lunch_allowance || 0.0;
                document.getElementById('other_allowances').value = data.other_allowances || 0.0;
                document.getElementById('total_earnings').value = data.total_earnings || 0.0;
                document.getElementById('tax_rate').value = data.tax_rate || 0.0;
                document.getElementById('napsa').value = data.napsa || 0.0;
                document.getElementById('nhima').value = data.nhima || 0.0;
                document.getElementById('advance').value = data.advance || 0.0;
                document.getElementById('umuz_fee').value = data.umuz_fee || 0.0;
                document.getElementById('double_deducted').value = data.double_deducted || 0.0;
                document.getElementById('total_deductions').value = data.total_deductions || 0.0;
                document.getElementById('net_pay').value = data.net_pay || 0.0;
                document.getElementById('tax_paid_ytd').value = data.tax_paid_ytd || 0.0;
                document.getElementById('taxable_earnings_ytd').value = data.taxable_earnings_ytd || 0.0;
                document.getElementById('annual_leave_due').value = data.annual_leave_due || 0.0;
                document.getElementById('leave_value_ytd').value = data.leave_value_ytd || 0.0;
                document.getElementById('pay_period').value = data.pay_period || '';

                calculatePayslip();
            })
            .catch(error => {
                console.error('Error fetching payslip data:', error);
                alert('Failed to load payslip data.');
            });
        });

        // Handle payslip generation
        document.getElementById('confirmPayslip').addEventListener('click', function () {
            const formData = new FormData(form);
            const employeeId = this.getAttribute('data-employee-id');
            const url = '{{ route("employees.generatePayslip", ":id") }}'.replace(':id', employeeId);
            const employeeName = '{{ $employee->employee_full_name }}';

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/pdf'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to generate PDF');
                return response.blob();
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `${employeeName}_payslip.pdf`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
                // bootstrap.Modal.getInstance(payslipModal).hide();ab
            })
            .catch(error => {
                console.error('Error generating PDF:', error);
                alert('Failed to generate payslip PDF.');
            });
        });
    });
</script>
@endsection


