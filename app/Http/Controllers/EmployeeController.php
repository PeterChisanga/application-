<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payslip;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller {
    public function index(Request $request) {
        try {
            $query = Employee::orderBy('employee_full_name', 'asc');

            if ($request->has('search') && !empty($request->input('search'))) {
                $searchTerm = $request->input('search');
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('employee_full_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('employee_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('designation', 'like', '%' . $searchTerm . '%');
                });
            }

            $employees = $query->get();

            return view('employees.index', compact('employees'));
        } catch (\Exception $e) {
            \Log::error('Error fetching employees: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load employees. Please try again later.');
        }
    }

    public function create() {
        try {
            return view('employees.create');
        } catch (\Exception $e) {
            \Log::error('Error loading create form: ' . $e->getMessage());
            return redirect()->route('employees.index')->with('error', 'Failed to load the create form. Please try again.');
        }
    }

    public function store(Request $request) {
        try {
            $validated = $request->validate([
                // Personal Details
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'surname_name' => 'required|string|max:255',
                'sex' => 'required|string|max:255',
                'phone_number' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'address' => 'required|string|max:255',
                'town' => 'required|string|max:255',
                'marital_status' => 'required|string|max:255',
                'nationality' => 'required|string|max:255',
                'date_of_birth' => 'required|date|before:today',

                // Employment Dates
                'date_of_joining' => 'required|date',
                'date_of_contract' => 'required|date',
                'date_of_termination_of_contract' => 'required|date|after:date_of_contract',

                // Identification Details
                'employee_id' => 'required|string|max:255|unique:employees,employee_id',
                'nhima_identification_number' => 'nullable|string|max:255',
                'tpin_number' => 'nullable|string|max:255',
                'nrc_or_passport_number' => 'required|string|max:255',

                // Job Details
                'designation' => 'required|string|max:255',
                'department' => 'required|string|max:255',
                'section' => 'required|string|max:255',
                'grade' => 'nullable|string|max:255',
                'status' => 'required|in:Active,Inactive,On Leave,Terminated',

                // Salary Details
                'basic_salary' => 'required|numeric|min:0',
                'other_allowances' => 'nullable|numeric|min:0',

                // References
                'references' => 'nullable|array',
                'references.*.name' => 'nullable|string|max:255',
                'references.*.phone_number' => 'nullable|string|max:255',

                // Payment Details
                'payment_method' => 'required|string|max:255',
                'social_security_number' => 'nullable|string|max:255',

                // Bank Details
                'account_name' => 'nullable|string|max:255',
                'ifsc_code' => 'nullable|string|max:255',
                'bank_name' => 'nullable|string|max:255',
                'branch_name' => 'nullable|string|max:255',
                'bank_address' => 'nullable|string|max:255',
                'bank_telephone_number' => 'nullable|string|max:255',
                'bank_account_number' => 'nullable|string|max:255',
            ]);

            //convert some fields to uppercase
            $validated['first_name'] = strtoupper($validated['first_name']);
            $validated['middle_name'] = strtoupper($validated['middle_name'] ?? '');
            $validated['surname_name'] = strtoupper($validated['surname_name']);
            $validated['address'] = strtoupper($validated['address']);
            $validated['town'] = strtoupper($validated['town']);
            $validated['designation'] = strtoupper($validated['designation']);
            $validated['department'] = strtoupper($validated['department']);
            $validated['section'] = strtoupper($validated['section']);
            $validated['payment_method'] = strtoupper($validated['payment_method'] ?? '');
            $validated['bank_name'] = strtoupper($validated['bank_name'] ?? '');

            $validated['employee_full_name'] = trim(
                $validated['first_name'] . ' ' .
                ($validated['middle_name'] ? $validated['middle_name'] . ' ' : '') .
                $validated['surname_name']
            );

            // Calculate fixed allowances
            $validated['housing_allowance'] = $validated['basic_salary'] * 0.3; // 30% of basic salary
            $validated['transport_allowance'] = 200; // Fixed value
            $validated['food_allowance'] = 180; // Fixed value

            $employee = Employee::create($validated);

            $grossEarnings = $employee->basic_salary
                + $employee->housing_allowance
                + $employee->transport_allowance
                + ($employee->other_allowances ?? 0)
                + $employee->food_allowance;

            // Deductions
            $napsa = $grossEarnings * 0.05; // NAPSA contribution (5% of gross earnings)
            $nhima = $employee->basic_salary * 0.01; // NHIMA contribution (1% of basic salary)

            // ZRA tax calculation
            if ($grossEarnings > 9200) {
                $zra = (5100 * 0) + (2000 * 0.2) + (2100 * 0.3) + (($grossEarnings - 9200) * 0.37);
            } elseif ($grossEarnings > 7100) {
                $zra = (5100 * 0) + (2000 * 0.2) + (($grossEarnings - 7100) * 0.3);
            } elseif ($grossEarnings > 5100) {
                $zra = (5100 * 0) + (($grossEarnings - 5100) * 0.2);
            } else {
                $zra = 0;
            }

            $totalDeductions = $zra + $nhima + $napsa;
            $netPay = $grossEarnings - $totalDeductions;

            // Create payslip
            Payslip::create([
                'employee_id' => $employee->id,
                'gross_earnings' => $grossEarnings,
                'total_deductions' => $totalDeductions,
                'net_pay' => $netPay,
                'napsa_contribution' => $napsa,
                'nhima' => $nhima,
                'tax_deduction' => $zra,
            ]);

            return redirect()->route('employees.index')->with('success', 'Employee added successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Error storing employee: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add employee: ' . $e->getMessage())->withInput();
        }
    }

    public function show($employee) {
        try {
            $employee = Employee::with('payslips')->findOrFail($employee);
            return view('employees.show', compact('employee'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('employees.index')->with('error', 'Employee not found.');
        } catch (\Exception $e) {
            \Log::error('Error showing employee: ' . $e->getMessage());
            return redirect()->route('employees.index')->with('error', 'Failed to load employee details. Please try again.');
        }
    }

    public function edit(Employee $employee) {
        try {
            return view('employees.edit', compact('employee'));
        } catch (\Exception $e) {
            \Log::error('Error loading edit form: ' . $e->getMessage());
            return redirect()->route('employees.index')->with('error', 'Failed to load the edit form. Please try again.');
        }
    }

    public function update(Request $request, Employee $employee) {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'surname_name' => 'required|string|max:255',
                'sex' => 'required|string|max:255',
                'phone_number' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'address' => 'required|string|max:255',
                'town' => 'required|string|max:255',
                'marital_status' => 'required|string|max:255',
                'nationality' => 'required|string|max:255',
                'date_of_birth' => 'required|date|before:today',

                'date_of_joining' => 'required|date',
                'date_of_contract' => 'required|date',
                'date_of_termination_of_contract' => 'required|date|after:date_of_contract',

                'employee_id' => "required|string|max:255|unique:employees,employee_id,{$employee->id}",
                'nhima_identification_number' => 'nullable|string|max:255',
                'tpin_number' => 'nullable|string|max:255',
                'nrc_or_passport_number' => 'required|string|max:255',

                'designation' => 'required|string|max:255',
                'department' => 'required|string|max:255',
                'section' => 'required|string|max:255',
                'grade' => 'nullable|string|max:255',
                'status' => 'required|in:Active,Inactive,On Leave,Terminated',

                // Salary Details
                'basic_salary' => 'required|numeric|min:0',
                'food_allowance' => 'required|numeric|min:0',
                'housing_allowance' => 'required|numeric|min:0',
                'transport_allowance' => 'required|numeric|min:0',
                'other_allowances' => 'nullable|numeric|min:0',

                // References
                'references' => 'nullable|array',
                'references.*.name' => 'nullable|string|max:255',
                'references.*.phone_number' => 'nullable|string|max:255',

                // Payment Details
                'payment_method' => 'required|string|max:255',
                'social_security_number' => 'nullable|string|max:255',

                // Bank Details
                'account_name' => 'nullable|string|max:255',
                'ifsc_code' => 'nullable|string|max:255',
                'bank_name' => 'nullable|string|max:255',
                'branch_name' => 'nullable|string|max:255',
                'bank_address' => 'nullable|string|max:255',
                'bank_telephone_number' => 'nullable|string|max:255',
                'bank_account_number' => 'nullable|string|max:255',
            ]);

            //convert some fields to uppercase
            $validated['first_name'] = strtoupper($validated['first_name']);
            $validated['middle_name'] = strtoupper($validated['middle_name'] ?? '');
            $validated['surname_name'] = strtoupper($validated['surname_name']);
            $validated['nationality'] = strtoupper($validated['nationality']);
            $validated['address'] = strtoupper($validated['address']);
            $validated['town'] = strtoupper($validated['town']);
            $validated['designation'] = strtoupper($validated['designation']);
            $validated['department'] = strtoupper($validated['department']);
            $validated['section'] = strtoupper($validated['section']);
            $validated['bank_name'] = strtoupper($validated['bank_name'] ?? '');

            $validated['employee_full_name'] = trim(
                $validated['first_name'] . ' ' .
                ($validated['middle_name'] ? $validated['middle_name'] . ' ' : '') .
                $validated['surname_name']
            );

            // Calculate fixed allowances
            $validated['housing_allowance'] = $validated['basic_salary'] * 0.3; // 30% of basic salary
            $validated['transport_allowance'] = 200; // Fixed value
            $validated['food_allowance'] = 180; // Fixed value

            $employee->update($validated);

            $grossEarnings = $employee->basic_salary
                + $employee->housing_allowance
                + $employee->transport_allowance
                + ($employee->other_allowances ?? 0)
                + $employee->food_allowance;

            // Deductions
            $napsa = $grossEarnings * 0.05; // NAPSA contribution (5% of gross earnings)
            $nhima = $employee->basic_salary * 0.01; // NHIMA contribution (1% of basic salary)

            // ZRA tax calculation
            if ($grossEarnings > 9200) {
                $zra = (5100 * 0) + (2000 * 0.2) + (2100 * 0.3) + (($grossEarnings - 9200) * 0.37);
            } elseif ($grossEarnings > 7100) {
                $zra = (5100 * 0) + (2000 * 0.2) + (($grossEarnings - 7100) * 0.3);
            } elseif ($grossEarnings > 5100) {
                $zra = (5100 * 0) + (($grossEarnings - 5100) * 0.2);
            } else {
                $zra = 0;
            }

            $totalDeductions = $zra + $nhima + $napsa;
            $netPay = $grossEarnings - $totalDeductions;

            // Update or create payslip
            $payslip = Payslip::where('employee_id', $employee->id)->first();
            if ($payslip) {
                $payslip->update([
                    'gross_earnings' => $grossEarnings,
                    'total_deductions' => $totalDeductions,
                    'net_pay' => $netPay,
                    'napsa_contribution' => $napsa,
                    'nhima' => $nhima,
                    'tax_deduction' => $zra,
                ]);
            } else {
                Payslip::create([
                    'employee_id' => $employee->id,
                    'gross_earnings' => $grossEarnings,
                    'total_deductions' => $totalDeductions,
                    'net_pay' => $netPay,
                    'napsa_contribution' => $napsa,
                    'nhima' => $nhima,
                    'tax_deduction' => $zra,
                ]);
            }

            return redirect()->route('employees.show', $employee->id)->with('success', 'Employee Information updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Error updating employee: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update employee: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Employee $employee) {
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function generatePayslip(Request $request, $employeeId) {
        try {
            $employee = Employee::with('payslips')->findOrFail($employeeId);

            if ($request->isMethod('get')) {
                $payslip = $employee->payslips->first();
                if (!$payslip) {
                    // Default payslip data if none exists, based on store logic + template fields
                    $grossEarnings = $employee->basic_salary
                        + $employee->housing_allowance
                        + $employee->transport_allowance
                        + $employee->food_allowance
                        + ($employee->other_allowances ?? 0);

                    $napsa = $grossEarnings * 0.05;
                    $nhima = $employee->basic_salary * 0.01;

                    if ($grossEarnings > 9200) {
                        $zra = (5100 * 0) + (2000 * 0.2) + (2100 * 0.3) + (($grossEarnings - 9200) * 0.37);
                    } elseif ($grossEarnings > 7100) {
                        $zra = (5100 * 0) + (2000 * 0.2) + (($grossEarnings - 7100) * 0.3);
                    } elseif ($grossEarnings > 5100) {
                        $zra = (5100 * 0) + (($grossEarnings - 5100) * 0.2);
                    } else {
                        $zra = 0;
                    }

                    $totalDeductions = $zra + $nhima + $napsa;
                    $netPay = $grossEarnings - $totalDeductions;

                    $payslipData = [
                        // DB-retrieved fields
                        'basic_salary' => $employee->basic_salary,
                        'housing_allowance' => $employee->housing_allowance,
                        'transport_allowance' => $employee->transport_allowance,
                        'lunch_allowance' => $employee->food_allowance, // Renamed to match template
                        'other_allowances' => $employee->other_allowances ?? 0,
                        'napsa' => $napsa,
                        'nhima' => $nhima,
                        'tax_rate' => $zra, // Renamed from tax_deduction to match template
                        'total_earnings' => $grossEarnings,
                        'total_deductions' => $totalDeductions,
                        'net_pay' => $netPay,
                        // Manual-entry fields (default values)
                        'days_worked' => 26,
                        'leave_days' => 0,
                        'leave_value' => 0,
                        'overtime_hours' => 0,
                        'overtime_pay' => 0,
                        'advance' => 0,
                        'umuz_fee' => 0,
                        'double_deducted' => 0,
                        'forced_leave' => 0,
                        'tax_paid_ytd' => 0,
                        'taxable_earnings_ytd' => 0,
                        'annual_leave_due' => 0,
                        'leave_value_ytd' => 0,
                        'pay_period' => date('Y-m-d'),
                        'prepared_by' => '',
                    ];
                } else {
                    $payslipData = [
                        // DB-retrieved fields
                        'basic_salary' => $employee->basic_salary,
                        'housing_allowance' => $employee->housing_allowance,
                        'transport_allowance' => $employee->transport_allowance,
                        'lunch_allowance' => $employee->food_allowance,
                        'other_allowances' => $employee->other_allowances ?? 0,
                        'napsa' => $payslip->napsa_contribution,
                        'nhima' => $payslip->nhima,
                        'tax_rate' => $payslip->tax_deduction,
                        'total_earnings' => $payslip->gross_earnings,
                        'total_deductions' => $payslip->total_deductions,
                        'net_pay' => $payslip->net_pay,
                        // Manual-entry fields (default values)
                        'days_worked' => 26,
                        'leave_days' => 0,
                        'leave_value' => 0,
                        'overtime_hours' => 0,
                        'overtime_pay' => 0,
                        'sundays_worked' => 0,
                        'sundays_pay' => 0,
                        'advance' => 0,
                        'umuz_fee' => 0,
                        'double_deducted' => 0,
                        'forced_leave' => 0,
                        'tax_paid_ytd' => 0,
                        'taxable_earnings_ytd' => 0,
                        'annual_leave_due' => 0,
                        'leave_value_ytd' => 0,
                        'pay_period' => date('Y-m-d'),
                    ];
                }
                return response()->json($payslipData);
            }

            if ($request->isMethod('post')) {
                // Validate all fields from the template
                $validated = $request->validate([
                    'basic_salary' => 'required|numeric|min:0',
                    'housing_allowance' => 'required|numeric|min:0',
                    'transport_allowance' => 'required|numeric|min:0',
                    'lunch_allowance' => 'required|numeric|min:0',
                    'other_allowances' => 'nullable|numeric|min:0',
                    'days_worked' => 'required|numeric|min:0',
                    'leave_days' => 'required|numeric|min:0',
                    'leave_value' => 'nullable|numeric|min:0',
                    'overtime_hours' => 'required|numeric|min:0',
                    'overtime_pay' => 'required|numeric|min:0',
                    'total_earnings' => 'required|numeric|min:0',
                    'sundays_worked' => 'required|numeric|min:0',
                    'sundays_pay' => 'required|numeric|min:0',
                    'tax_rate' => 'required|numeric|min:0',
                    'napsa' => 'required|numeric|min:0',
                    'nhima' => 'required|numeric|min:0',
                    'advance' => 'required|numeric|min:0',
                    'umuz_fee' => 'required|numeric|min:0',
                    'double_deducted' => 'required|numeric|min:0',
                    'forced_leave' => 'required|numeric|min:0',
                    'total_deductions' => 'required|numeric|min:0',
                    'net_pay' => 'required|numeric|min:0',
                    'tax_paid_ytd' => 'required|numeric|min:0',
                    'taxable_earnings_ytd' => 'required|numeric|min:0',
                    'annual_leave_due' => 'required|numeric|min:0',
                    'leave_value_ytd' => 'required|numeric|min:0',
                    'pay_period' => 'required|date',
                ]);

                $payslipData = $validated;

                $pdf = Pdf::loadView('payslips.template', compact('employee', 'payslipData'));
                return $pdf->download("{$employee->employee_full_name}_payslip.pdf");
            }

        } catch (\Exception $e) {
            Log::error('Error in generatePayslip: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to process payslip: ' . $e->getMessage()], 500);
        }
    }

    public function printEmployeeInformation($employeeId){
        try {
            $employee = Employee::with('payslips')->findOrFail($employeeId);

            // Generate the PDF
            $pdf = Pdf::loadView('reports.employee-information', compact('employee'));
            return $pdf->download("{$employee->employee_full_name}_employee_information.pdf");

        } catch (\Exception $e) {
            Log::error('Error generating employee information PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate employee information PDF. Please try again.');
        }
    }
}

