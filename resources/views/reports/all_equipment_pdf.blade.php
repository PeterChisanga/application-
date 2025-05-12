<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Equipment Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 15px;
        }
        .container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 14px;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #d3d3d3;
            font-weight: bold;
            text-align: center;
        }
        .right-align {
            text-align: right;
        }
        .summary {
            margin-top: 15px;
            width: 50%;
            float: right;
        }
        .summary p {
            margin: 4px 0;
            font-weight: bold;
            font-size: 11px;
        }
        .summary .value {
            float: right;
        }
        /* Column widths for better balance in landscape */
        th:nth-child(1), td:nth-child(1) { width: 5%; } /* S No. */
        th:nth-child(2), td:nth-child(2) { width: 15%; } /* Description */
        th:nth-child(3), td:nth-child(3) { width: 10%; } /* Type */
        th:nth-child(4), td:nth-child(4) { width: 20%; } /* Locations */
        th:nth-child(5), td:nth-child(5) { width: 10%; } /* Hours/Kms */
        th:nth-child(6), td:nth-child(6) { width: 10%; } /* Material (MT) */
        th:nth-child(7), td:nth-child(7) { width: 8%; } /* Number of Trips */
        th:nth-child(8), td:nth-child(8) { width: 10%; } /* Fuel Used */
        th:nth-child(9), td:nth-child(9) { width: 10%; } /* Average Fuel Consumed */
        th:nth-child(10), td:nth-child(10) { width: 9%; } /* Fuel Cost */
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>All Equipment Report</h1>
            <p>Period: {{ \Carbon\Carbon::parse($startDate)->format('Y/m/d') }} to {{ \Carbon\Carbon::parse($endDate)->format('Y/m/d') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>S No.</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Locations</th>
                    <th>Number of Trips</th>
                    <th>Hours/Kms</th>
                    <th>Material (MT)</th>
                    <th>Fuel Used (Litres)</th>
                    <th>Average Fuel Consumed</th>
                    <th>Fuel Cost (ZMW)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reportData as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item['registration_number'] }} {{ $item['equipment_name'] }}</td>
                        <td>{{ $item['equipment_type'] }}</td>
                        <td>{{ $item['locations'] ?: '-' }}</td>
                        <td class="right-align">{{ $item['equipment_type'] === 'Machinery' ? '-' : $item['number_of_trips'] }}</td>
                        <td class="right-align">{{ number_format($item['total_distance_or_hours'], 2) }}</td>
                        <td class="right-align">{{ $item['equipment_type'] === 'HMV' ? number_format($item['total_material_delivered'], 2) : '-' }}</td>
                        <td class="right-align">{{ number_format($item['total_fuel_used'], 2) }}</td>
                        <td class="right-align">{{ $item['average_fuel_consumed'] === '-' ? '-' : number_format($item['average_fuel_consumed'], 2) }} {{ $item['equipment_type'] === 'Machinery' ? 'ltrs/hr' : 'ltrs/km' }}</td>
                        <td class="right-align">{{ $item['total_fuel_cost'] === '-' ? '-' : number_format($item['total_fuel_cost'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <p>Total Material Delivered: <span class="value">{{ number_format($summary['total_material_delivered'], 2) }} Tonnes</span></p>
            <p>Total Fuel Used: <span class="value">{{ number_format($summary['total_fuel_used'], 2) }} Litres</span></p>
            <p>Total Fuel Cost: <span class="value">{{ $summary['total_fuel_cost'] === '-' ? '-' : number_format($summary['total_fuel_cost'], 2) . ' ZMW' }}</span></p>
        </div>
    </div>
</body>
</html>
