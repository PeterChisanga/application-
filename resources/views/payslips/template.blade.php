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

           .payslip th, .payslip td {
                /* padding: 4px; */
                border: none;
                font-size: 7px;
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
        </style>
    </head>
    <body>
        <div class="payslip">
            <!-- Employee Information -->
            <table>
                <tr>
                    <th>EMP NR:</th>
                    <td>{{ $employee->employee_id }}</td>
                    <th>PAY POINT:</th>
                    <td>KITWE</td>
                    <th>Pay Period:</th>
                    <td>{{ \Carbon\Carbon::parse($payslipData['pay_period'])->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <th>EMP NAME:</th>
                    <td>{{ $employee->first_name }}</td>
                    <th>PAY GRADE:</th>
                    <td>{{ $employee->grade }}</td>
                    <th>CO NAME</th>
                    <td>SWARNA METALS (Z) LTD</td>
                </tr>
                <tr>
                    <th>KNOWN AS:</th>
                    <td>{{ $employee->employee_full_name }}</td>
                    <th>BASIC RATE:</th>
                    <td>{{ number_format($employee->basic_salary, 2) }} ZMW</td>
                    <th>CO ADDRESS:</th>
                    <td>FARM SUB-A 4213 KITWE</td>
                </tr>
                <tr>
                    <th>DATE ENGAGED:</th>
                    <td>{{ \Carbon\Carbon::parse($employee->date_of_joining)->format('Y-m-d') }}</td>
                    <th>PLANT SITE:</th>
                    <td>SALAMANO</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>ACCOUNT NO:</th>
                    <td>{{ $employee->bank_account_number }}</td>
                    <th>TERM DATE:</th>
                    <td>{{ $employee->term_date ?? '-' }}</td>
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
                    <!-- First row -->
                    <tr>
                        <td>Basic Pay</td>
                        <td>{{ number_format($payslipData['days_worked'], 2) }}</td>
                        <td>{{ number_format($payslipData['basic_salary'], 2) }}</td>
                        <td></td>
                        <td>TAX RATE</td>
                        <td>{{ number_format($payslipData['tax_rate'], 2) }}</td>
                    </tr>
                    <!-- Second row -->
                    <tr>
                        <td>Leave Days</td>
                        <td>{{ number_format($payslipData['leave_days']) }}</td>
                        <td></td>
                        <td></td>
                        <td>NAPSA</td>
                        <td>{{ number_format($payslipData['napsa'], 2) }}</td>
                    </tr>
                    <!-- Third row -->
                    <tr>
                        <td>Leave Value</td>
                        <td>{{ number_format($payslipData['leave_value']) ?? '-' }}</td>
                        <td></td>
                        <td></td>
                        <td>NHIMA</td>
                        <td>{{ number_format($payslipData['nhima'], 2) }}</td>
                    </tr>
                    <!-- Fourth row -->
                    <tr>
                        <td>Housing Allowance</td>
                        <td></td>
                        <td>{{ number_format($payslipData['housing_allowance'], 2) }}</td>
                        <td></td>
                        <td>ADVANCE</td>
                        <td>{{ number_format($payslipData['advance'], 2) }}</td>
                    </tr>
                    <!-- Fifth row -->
                    <tr>
                        <td>Overtime</td>
                        <td>{{ number_format($payslipData['overtime_hours'], 2) }}</td>
                        <td>{{ number_format($payslipData['overtime_pay'], 2) }}</td>
                        <td></td>
                        <td>UMUZ FEE</td>
                        <td>{{ number_format($payslipData['umuz_fee'], 2) }}</td>
                    </tr>
                    <!-- Sixth row -->
                    <tr>
                        <td>Transport</td>
                        <td></td>
                        <td>{{ number_format($payslipData['transport_allowance'], 2) }}</td>
                        <td></td>
                        <td>DOUBLE DEDUCTED</td>
                        <td>{{ number_format($payslipData['double_deducted'], 2) }}</td>
                    </tr>
                    <!-- Seventh row -->
                    <tr>
                        <td>Lunch</td>
                        <td></td>
                        <td>{{ number_format($payslipData['lunch_allowance'], 2) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <!-- Totals row -->
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
                    <tr class="header-row">
                        <th>Net Pay:</th>
                        <th>{{ number_format($payslipData['net_pay'], 2) }} ZMW</th>
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
                        <th>{{ number_format($payslipData['tax_paid_ytd'], 2) }}</th>
                    </tr>
                    <tr>
                        <th>Taxable Earnings</th>
                        <th>{{ number_format($payslipData['taxable_earnings_ytd'], 2) }}</th>
                    </tr>
                    <tr>
                        <th>Annual Leave Due</th>
                        <th>{{ number_format($payslipData['annual_leave_due'], 2) }}</th>
                    </tr>
                    <tr>
                        <th>Leave Value</th>
                        <th>{{ number_format($payslipData['leave_value_ytd'], 2) }}</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
