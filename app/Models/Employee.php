<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'surname_name',
        'employee_full_name',
        'sex',
        'phone_number',
        'email',
        'address',
        'town',
        'marital_status',
        'nationality',
        'date_of_birth',
        'date_of_joining',
        'date_of_contract',
        'date_of_termination_of_contract',
        'employee_id',
        'nhima_identification_number',
        'tpin_number',
        'nrc_or_passport_number',
        'designation',
        'department',
        'section',
        'grade',
        'basic_salary',
        'housing_allowance',
        'transport_allowance',
        'other_allowances',
        'food_allowance',
        'references',
        'payment_method',
        'social_security_number',
        'account_name',
        'ifsc_code',
        'bank_name',
        'branch_name',
        'bank_address',
        'bank_telephone_number',
        'bank_account_number',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_joining' => 'date',
        'date_of_contract' => 'date',
        'date_of_termination_of_contract' => 'date',
        'basic_salary' => 'decimal:2',
        'housing_allowance' => 'decimal:2',
        'transport_allowance' => 'decimal:2',
        'other_allowances' => 'decimal:2',
        'food_allowance' => 'decimal:2',
        'references' => 'array',
    ];

    public function payslips()
    {
        return $this->hasMany(Payslip::class, 'employee_id');
    }

    public function overtimes()
    {
        return $this->hasMany(Overtime::class, 'employee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tripsAsDriver()
    {
        return $this->hasMany(Trip::class, 'driver_id');
    }

    public function machineryUsagesAsOperator()
    {
        return $this->hasMany(MachineryUsage::class, 'operator_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }
}
