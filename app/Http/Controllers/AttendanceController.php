<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request) {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $employees = Employee::with(['attendanceRecords' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }])->get();

        return view('attendance.index', compact('employees', 'month', 'year'));
    }

    public function store(Request $request) {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:P,A,SUN,SP,FL,SL',
            'overtime_hours' => 'nullable|numeric|min:0',
        ]);

        AttendanceRecord::updateOrCreate(
            ['employee_id' => $request->employee_id, 'date' => $request->date],
            ['status' => $request->status, 'overtime_hours' => $request->overtime_hours]
        );

        return redirect()->back()->with('success', 'Attendance recorded successfully.');
    }

    public function report(Request $request) {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $format = $request->input('format', 'csv');

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $employees = Employee::with(['attendanceRecords' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }])->get();

        if ($format === 'csv') {
            return $this->generateCSV($employees, $startDate, $endDate);
        }

        return redirect()->back()->with('error', 'Invalid format selected.');
    }

    private function generateCSV($employees, $startDate, $endDate) {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Title
        $sheet->setCellValue('A1', "Employee Attendance Report - {$startDate->format('F Y')}");
        $sheet->mergeCells('A1:AM1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Headers
        $headers = ['S/No.', 'Emp No.', 'Full Name'];
        for ($day = $startDate->day; $day <= $endDate->day; $day++) {
            $headers[] = $day;
        }
        $headers = array_merge($headers, [
            'Total Days', 'Days Present', 'Sunday Pay', 'Forced Leave', 'Sick Leave', 'Absent', 'OT Hours', 'Emp No. & Name'
        ]);
        $sheet->fromArray($headers, null, 'A2');

        // Data
        $row = 3;
        foreach ($employees as $index => $employee) {
            $records = $employee->attendanceRecords->pluck('status', 'date')->all();
            $data = [
                $index + 1,
                $employee->employee_number,
                $employee->full_name,
            ];

            for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                $data[] = $records[$date->toDateString()] ?? 'A';
            }

            $totalDays = $endDate->diffInDays($startDate) + 1;
            $daysPresent = $employee->attendanceRecords->whereIn('status', ['P', 'SP'])->count();
            $sundayPay = $employee->attendanceRecords->where('status', 'SP')->count();
            $forcedLeave = $employee->attendanceRecords->where('status', 'FL')->count();
            $sickLeave = $employee->attendanceRecords->where('status', 'SL')->count();
            $absent = $totalDays - ($daysPresent + $forcedLeave + $sickLeave);
            $otHours = $employee->attendanceRecords->sum('overtime_hours');

            $data = array_merge($data, [
                $totalDays,
                $daysPresent,
                $sundayPay,
                $forcedLeave,
                $sickLeave,
                $absent,
                $otHours,
                "{$employee->employee_number} {$employee->full_name}"
            ]);

            $sheet->fromArray($data, null, "A{$row}");
            $row++;
        }

        $fileName = "attendance_report_{$startDate->format('Y_m')}.xlsx";
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filePath = storage_path("app/public/{$fileName}");
        $writer->save($filePath);

        return response()->download($filePath, $fileName)->deleteFileAfterSend();
    }
}