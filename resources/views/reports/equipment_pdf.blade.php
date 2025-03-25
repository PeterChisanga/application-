<!DOCTYPE html>
<html>
<head>
    <title>Equipment Report - {{ $equipment->registration_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #d3d3d3; font-weight: bold; text-align: center; }
        .summary { margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <h1>{{ $equipment->registration_number }} - {{ $equipment->equipment_name }} - {{ $equipment->type }} - {{ $month }}/{{ $year }}</h1>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Departure Date</th>
                <th>Return Date</th>
                <th>Start Km</th>
                <th>Close Km</th>
                <th>Distance Travelled</th>
                <th>Location</th>
                <th>Material Delivered</th>
                <th>Material Qty (tonnes)</th>
                <th>Fuel Logs</th>
                <th>Total Fuel Used (Litres)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalFuelUsed = 0;
                $totalDistanceTravelled = 0;
                $totalMaterialDelivered = 0;
            @endphp
            @foreach ($data as $trip)
                @php
                    $distanceTravelled = ($trip->end_kilometers && $trip->start_kilometers) ? ($trip->end_kilometers - $trip->start_kilometers) : 0;
                    $materialDelivered = $trip->quantity ?? 0;
                    $totalFuelUsed += $trip->fuels->sum('litres_added');
                    $totalDistanceTravelled += $distanceTravelled;
                    $totalMaterialDelivered += $materialDelivered;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $trip->departure_date->format('Y/m/d') }}</td>
                    <td>{{ $trip->return_date ? $trip->return_date->format('Y/m/d') : '-' }}</td>
                    <td>{{ number_format($trip->start_kilometers) }}</td>
                    <td>{{ $trip->end_kilometers ? number_format($trip->end_kilometers) : '-' }}</td>
                    <td>{{ number_format($distanceTravelled) }} km</td>
                    <td>{{ $trip->location }}</td>
                    <td>{{ $trip->material_delivered ?? '-' }}</td>
                    <td>{{ number_format($materialDelivered, 2) }}</td>
                    <td>
                        @if ($trip->fuels->isEmpty())
                            No fuel data
                        @else
                            {{ $trip->fuels->map(fn($fuel) => number_format($fuel->litres_added, 2) . 'L at ' . ($fuel->refuel_location ?? '-'))->implode(' | ') }}
                        @endif
                    </td>
                    <td>{{ number_format($trip->fuels->sum('litres_added'), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p>Total Distance Travelled: {{ number_format($totalDistanceTravelled, 2) }} Km</p>
        <p>Total Fuel Used: {{ number_format($totalFuelUsed, 2) }} Litres</p>
        <p>Total Material Delivered: {{ number_format($totalMaterialDelivered, 2) }} Tonnes</p>
    </div>
</body>
</html>

