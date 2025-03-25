<?php

namespace App\Imports;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class AttendanceImport implements ToModel, WithHeadingRow
{
    private $month;
    private $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function model(array $row)
    {
        // Create or find employee
        $employee = Employee::firstOrCreate(
            ['employee_number' => $row['emp_no']],
            ['full_name' => $row['full_name']]
        );

        // Map day columns (27 to 26) to dates
        $attendanceRecords = [];
        for ($day = 27; $day <= 31; $day++) { // January
            $date = Carbon::create($this->year, 1, $day);
            $status = strtoupper($row[(string)$day] ?? 'A'); // Default to Absent if empty
            $attendanceRecords[] = new AttendanceRecord([
                'employee_id' => $employee->id,
                'date' => $date,
                'status' => $status,
                'overtime_hours' => null, // Add later if OT tracked daily
            ]);
        }
        for ($day = 1; $day <= 26; $day++) { // February
            $date = Carbon::create($this->year, 2, $day);
            $status = strtoupper($row[(string)$day] ?? 'A');
            $attendanceRecords[] = new AttendanceRecord([
                'employee_id' => $employee->id,
                'date' => $date,
                'status' => $status,
                'overtime_hours' => null,
            ]);
        }

        // Set overtime hours from total (spread evenly or adjust logic as needed)
        $totalOvertime = (float)($row['total_no_of_ot_hours'] ?? 0);
        $daysPresent = (int)$row['total_no_of_days_present'];
        if ($totalOvertime > 0 && $daysPresent > 0) {
            $otPerDay = $totalOvertime / $daysPresent;
            foreach ($attendanceRecords as $record) {
                if ($record->status === 'P' || $record->status === 'SP') {
                    $record->overtime_hours = $otPerDay;
                }
            }
        }

        return $attendanceRecords;
    }

    public function headingRow(): int
    {
        return 3; // Data starts on row 3 in your Excel
    }
}