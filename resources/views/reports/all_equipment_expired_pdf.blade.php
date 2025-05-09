<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expired or Expiring Equipment Report</title>
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
        .center-align {
            text-align: center;
        }
        .highlight {
            color: red;
            text-decoration: underline;
            font-weight: bold;
        }
        /* Column widths for balance in landscape */
        th:nth-child(1), td:nth-child(1) { width: 5%; } /* S No. */
        th:nth-child(2), td:nth-child(2) { width: 30%; } /* Description */
        th:nth-child(3), td:nth-child(3) { width: 10%; } /* Type */
        th:nth-child(4), td:nth-child(4) { width: 13%; } /* Insurance */
        th:nth-child(5), td:nth-child(5) { width: 14%; } /* Road Tax */
        th:nth-child(6), td:nth-child(6) { width: 14%; } /* Fitness Tax */
        th:nth-child(7), td:nth-child(7) { width: 14%; } /* Identity Tax */
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Equipment with Expired or Expiring Insurance and Tax</h1>
            <p>As of {{ \Carbon\Carbon::today()->format('Y/m/d') }} (Includes items expiring by {{ \Carbon\Carbon::today()->addMonth()->endOfMonth()->format('Y/m/d') }})</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>S No.</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Insurance</th>
                    <th>Road Tax</th>
                    <th>Fitness Tax</th>
                    <th>Identity Tax</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reportData as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item['description'] }}</td>
                        <td>{{ $item['equipment_type'] }}</td>
                        <td class="center-align {{ $item['insurance_highlight'] ? 'highlight' : '' }}">{{ $item['insurance_expiry_date'] }}</td>
                        <td class="center-align {{ $item['road_tax_highlight'] ? 'highlight' : '' }}">{{ $item['road_tax_expiry_date'] }}</td>
                        <td class="center-align {{ $item['fitness_tax_highlight'] ? 'highlight' : '' }}">{{ $item['fitness_tax_expiry_date'] }}</td>
                        <td class="center-align {{ $item['identity_tax_highlight'] ? 'highlight' : '' }}">{{ $item['identity_tax_expiry_date'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
