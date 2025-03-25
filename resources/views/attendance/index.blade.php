<h1>Employee Attendance</h1>
<form method="GET" action="{{ route('attendance.report') }}">
    <label>Month:</label>
    <input type="number" name="month" min="1" max="12" value="{{ $month }}">
    <label>Year:</label>
    <input type="number" name="year" value="{{ $year }}">
    <button type="submit">Generate Report</button>
</form>

<table border="1">
    <thead>
        <tr>
            <th>Emp No.</th>
            <th>Full Name</th>
            @for ($day = 1; $day <= Carbon\Carbon::create($year, $month, 1)->daysInMonth; $day++)
                <th>{{ $day }}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach ($employees as $employee)
            <tr>
                <td>{{ $employee->employee_number }}</td>
                <td>{{ $employee->full_name }}</td>
                @for ($day = 1; $day <= Carbon\Carbon::create($year, $month, 1)->daysInMonth; $day++)
                    <td>
                        {{ $employee->attendanceRecords->where('date', Carbon\Carbon::create($year, $month, $day))->first()->status ?? 'A' }}
                    </td>
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>