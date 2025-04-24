<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Employee Payslip</title>
        <style>
            body {
                font-family: sans-serif;
            }

            .payslip {
                padding: 10px;
                margin: 10px auto;
                max-width: 700px;
                background: rgba(255, 255, 255, 0.78);
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }

            .center-text {
                text-align: center;
            }

            .logo {
                display: block;
                margin: 0 auto 10px;
                max-width: 80px;
                height: auto;
            }

            .company-header {
                text-align: center;
                margin-bottom: 15px;
            }

            .company-header h1 {
                margin: 0;
                color: #000000;
                font-size: 30px;
                font-weight: bold;
            }

            .payslip table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10px;
            }

            .payslip th {
                /* padding: 4px; */
                border: none;
                font-size: 12px;
                text-align: left;
            }

           .payslip td {
                /* padding: 4px; */
                border: none;
                font-size: 14px;
                text-align: left;
            }

            .payslip .bold {
                font-weight: bold;
            }

            .payslip .right {
                text-align: right;
            }

            .payslip p {
                font-size: 11px;
            }

            .payslip h4 {
                margin-bottom: 0px;
            }

            .tables-container {
                display: flex;
                gap: 10px;
            }

            .tables-container table {
                flex: 1;
            }

            .header-row th {
                border-bottom: 2px solid #000;
            }

            .net-pay th {
                border-bottom: 2px solid #000;
                text-align: right;
                font-size: 15px;
            }
        </style>
    </head>
    <body>
        <div class="payslip">
            <!-- Employee Information -->
            <table class="table table-bordered table-sm">
                <tr>
                    <td><strong>EMP NR:</strong> {{ $employee->employee_id ?? '-' }}</td>
                    <td><strong>PAY POINT:</strong> KITWE</td>
                    <td><strong>PAY PERIOD:</strong> {{ isset($payslipData['pay_period']) ? \Carbon\Carbon::parse($payslipData['pay_period'])->format('Y-m-d') : '-' }}</td>
                </tr>
                <tr>
                    <td><strong>EMP NAME:</strong> {{ $employee->first_name ?? '-' }} </td>
                    <td><strong>PAY GRADE:</strong> {{ $employee->grade ?? '-' }}</td>
                    <td><strong>CO NAME:</strong> SWARNA METALS (Z) LTD</td>
                </tr>
                <tr>
                    <td><strong>KNOWN AS:</strong> {{ $employee->employee_full_name ?? '-' }} </td>
                    <td><strong>BASIC RATE:</strong> {{ isset($employee->basic_salary) ? number_format($employee->basic_salary, 2) : '-' }} ZMW</td>
                    <td><strong>CO ADDRESS:</strong> FARM SUB-A 4213 KITWE</td>
                </tr>
                <tr>
                    <td><strong>DATE ENGAGED:</strong> {{ $employee->date_of_joining ? \Carbon\Carbon::parse($employee->date_of_joining)->format('Y-m-d') : '-' }}</td>
                    <td><strong>PLANT SITE:</strong> SALAMANO</td>
                    <td><strong>ACCOUNT NO:</strong> {{ $employee->bank_account_number ?? '-' }}</td>
                </tr>
                <tr>
                    <td><strong>TERM DATE:</strong> {{ $employee->term_date ? \Carbon\Carbon::parse($employee->term_date)->format('Y-m-d') : '-' }}</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <table>
                <thead>
                    <tr class="header-row">
                        <!-- Earnings headers -->
                        <th colspan="3"><h4>Earnings</h4></th>
                        <!-- Spacer -->
                        <th></th>
                        <!-- Deductions headers -->
                        <th colspan="2"><h4>Deductions</h4></th>
                    </tr>
                    <tr>
                        <!-- Earnings columns -->
                        <th>Description</th>
                        <th>Units</th>
                        <th>Amount (ZMW)</th>
                        <!-- Spacer -->
                        <th></th>
                        <!-- Deductions columns -->
                        <th>Description</th>
                        <th>Amount (ZMW)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Basic Pay</td>
                        <td>{{ number_format($payslipData['days_worked'], 2) }}</td>
                        <td>{{ number_format($payslipData['basic_salary'], 2) }}</td>
                        <td></td>
                        <td>TAX RATE</td>
                        <td>{{ number_format($payslipData['tax_rate'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Leave Days</td>
                        <td>{{ number_format($payslipData['leave_days']) }}</td>
                        <td>{{ number_format($payslipData['leave_value']) ?? '-' }}</td>
                        <td></td>
                        <td>NAPSA</td>
                        <td>{{ number_format($payslipData['napsa'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Housing Allowance</td>
                        <td></td>
                        <td>{{ number_format($payslipData['housing_allowance'], 2) }}</td>
                        <td></td>
                        <td>NHIMA</td>
                        <td>{{ number_format($payslipData['nhima'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Overtime</td>
                        <td>{{ number_format($payslipData['overtime_hours'], 2) }}</td>
                        <td>{{ number_format($payslipData['overtime_pay'], 2) }}</td>
                        <td></td>
                        <td>ADVANCE</td>
                        <td>{{ number_format($payslipData['advance'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Sunday / PH</td>
                        <td>{{ number_format($payslipData['sundays_worked'], 2) }}</td>
                        <td>{{ number_format($payslipData['sundays_pay'], 2) }}</td>
                        <td></td>
                        <td>UMUZ FEE</td>
                        <td>{{ number_format($payslipData['umuz_fee'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Forced Leave</td>
                        <td></td>
                        <td>{{ number_format($payslipData['forced_leave'], 2) }}</td>
                        <td></td>
                        <td>DOUBLE DEDUCTED</td>
                        <td>{{ number_format($payslipData['double_deducted'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Transport</td>
                        <td></td>
                        <td>{{ number_format($payslipData['transport_allowance'], 2) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Lunch</td>
                        <td></td>
                        <td>{{ number_format($payslipData['lunch_allowance'], 2) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="header-row">
                        <th colspan="2">Total Earnings</th>
                        <th>{{ number_format($payslipData['total_earnings'], 2) }}</th>
                        <td></td>
                        <th>Total Deductions</th>
                        <th>{{ number_format($payslipData['total_deductions'], 2) }}</th>
                    </tr>
                </tbody>
            </table>

            <table>
                <tbody>
                    <tr class="net-pay">
                        <th><span>Net Pay: </span>{{ number_format($payslipData['net_pay'], 2) }} ZMW</th>
                    </tr>
                </tbody>
            </table>

            <!-- Year-to-Date Totals -->
            <h4>Year-to-Date Totals</h4>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount (ZMW)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Tax Paid</th>
                        <th>{{ $payslipData['tax_paid_ytd'] > 0 ? number_format($payslipData['tax_paid_ytd'], 2) : '-'  }}</th>
                    </tr>
                    <tr>
                        <th>Taxable Earnings</th>
                        <th>{{ $payslipData['taxable_earnings_ytd'] > 0 ? number_format($payslipData['taxable_earnings_ytd'], 2) : '-' }}</th>
                    </tr>
                    <tr>
                        <th>Annual Leave Due</th>
                        <th>{{ $payslipData['annual_leave_due'] > 0 ? number_format($payslipData['annual_leave_due'], 2) : '-' }}</th>
                    </tr>
                    <tr>
                        <th>Leave Value</th>
                        <th>{{ $payslipData['leave_value_ytd'] > 0 ? number_format($payslipData['leave_value_ytd'], 2) : '-' }}</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
