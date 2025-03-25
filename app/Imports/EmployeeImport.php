<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Payslip;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class EmployeeImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithCalculatedFormulas {
    use SkipsFailures;

    public function model(array $row) {
         Log::debug("Row Data: " . json_encode($row));
        // Convert Excel date format (M/D/YYYY) to Y-m-d
        $dateFields = [
            'date_of_birth',
            'date_of_joining',
            'date_of_contract',
            'date_of_termination_of_contract',
        ];

        foreach ($dateFields as $field) {
            if (isset($row[$field]) && !empty($row[$field])) {
                Log::debug("Raw {$field} value: " . json_encode($row[$field]));

                try {
                    if (is_numeric($row[$field])) {
                        // Handle Excel serial date (e.g., 44927 for 1/1/2023)
                        $row[$field] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[$field]))->format('Y-m-d');
                    } else {
                        $row[$field] = Carbon::createFromFormat('m/d/Y', $row[$field])->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    Log::warning("Failed to parse {$field} with value '{$row[$field]}': " . $e->getMessage());
                    $row[$field] = null;
                }
            } else {
                $row[$field] = null;
            }
        }

        $data = [
            // Personal Details
            'first_name' => strtoupper($row['first_name']),
            'middle_name' => isset($row['middle_name']) && !empty($row['middle_name']) ? strtoupper($row['middle_name']) : null,
            'surname_name' => strtoupper($row['surname_name']),
            'sex' => $row['sex'],
            'phone_number' => $row['phone_number'] ?? null,
            'email' => $row['email'] ?? null,
            'address' => strtoupper($row['address']) ?? 'KITWE',
            'town' => strtoupper($row['town']) ?? 'KITWE',
            'marital_status' => $row['marital_status'] ?? 'single',
            'nationality' => $row['nationality'] ,
            'date_of_birth' => $row['date_of_birth'] ?? null,

            // Employment Dates
            'date_of_joining' => $row['date_of_joining'],
            'date_of_contract' => $row['date_of_contract'],
            'date_of_termination_of_contract' => '2025-12-31',

            // Identification Details
            'employee_id' => $row['employee_id'],
            'nhima_identification_number' => $row['nhima_identification_number'] ?? null,
            'tpin_number' => $row['tpin_number'] ?? null,
            'nrc_or_passport_number' => $row['nrc_or_passport_number'],

            // Job Details
            'designation' => strtoupper($row['designation']),
            'department' => strtoupper($row['department']),
            'section' => strtoupper($row['section']),
            'grade' => $row['grade'] ?? null,
            'status' => $row['status'],

            // Salary Details
            'basic_salary' => is_numeric($row['basic_salary']) ? (float)$row['basic_salary'] : 0,

            // Payment Details
            'payment_method' => $row['payment_method'] ,
            'social_security_number' => $row['social_security_number'] ?? null,

            // Bank Details
            'ifsc_code' => $row['ifsc_code'] ?? null,
            'bank_name' => 'INDO ZAMBIA BANK - IZB',
            'branch_name' => 'KITWE BRANCH',
            'bank_address' => 'ZAMBIA WAY ,KITWE, ZAMBIA',
            'bank_telephone_number' => '260 212 226088 / 226624',
            'bank_account_number' => $row['bank_account_number'] ?? null,
        ];

        // Generate employee_full_name
        $fullNameParts = [
            $data['first_name'],
            $data['middle_name'] ?? '',
            $data['surname_name'],
        ];
        $data['employee_full_name'] = strtoupper(trim(implode(' ', array_filter($fullNameParts))));
        $data['account_name'] = $data['employee_full_name'];

        // Calculate fixed allowances
        $data['housing_allowance'] = $data['basic_salary'] * 0.3; // 30% of basic salary
        $data['transport_allowance'] = 200; // Fixed value
        $data['food_allowance'] = 180; // Fixed value
        $data['other_allowances'] = 0; // Fixed value just for the bulk employee upload

        // Create or update the employee record
        $employee = Employee::updateOrCreate(
            ['employee_id' => $data['employee_id']], // Match by employee_id
            $data
        );

        // Calculate gross earnings
        $grossEarnings = $employee->basic_salary
            + $employee->housing_allowance
            + $employee->transport_allowance
            + ($employee->other_allowances ?? 0)
            + $employee->food_allowance;

        // Calculate deductions
        $napsa = $grossEarnings * 0.05; // NAPSA contribution (5% of gross earnings)
        $nhima = $employee->basic_salary * 0.01; // NHIMA contribution (1% of basic salary)

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

        // Create or update the payslip record
        Payslip::updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'gross_earnings' => $grossEarnings,
                'total_deductions' => $totalDeductions,
                'net_pay' => $netPay,
                'napsa_contribution' => $napsa,
                'nhima' => $nhima,
                'tax_deduction' => $zra,
            ]
        );

        return null; // Return null since we're handling creation manually
    }

    public function rules(): array {
        return [
            // Personal Details
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'surname_name' => 'required|string|max:255',
            'sex' => 'required|string|max:255',
            'phone_number' => 'nullable|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'town' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:255',
            'nationality' => 'required|string|max:255',
            'date_of_birth' => 'nullable|before:today',

            // Employment Dates
            'date_of_joining' => 'required',
            'date_of_contract' => 'required',
            'date_of_termination_of_contract' => 'required',

            // Identification Details
            'employee_id' => 'required|string|max:255',
            'nhima_identification_number' => 'nullable|max:255',
            'tpin_number' => 'nullable|max:255',
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

            // Payment Details
            'payment_method' => 'required|string|max:255',
            'social_security_number' => 'nullable|max:255',

            // Bank Details
            'ifsc_code' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'bank_address' => 'nullable|string|max:255',
            'bank_telephone_number' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|max:255',
        ];
    }
}
