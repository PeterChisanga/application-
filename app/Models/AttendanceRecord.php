<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'status',
        'overtime_hours',
    ];

    protected $casts = [
        'date' => 'date',
        'overtime_hours' => 'float',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}