<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model {
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'driver_id',
        'trip_number',
        'departure_date',
        'return_date',
        'start_kilometers',
        'end_kilometers',
        'material_delivered',
        'location',
        'quantity',
        'loading',
        'council_fee',
        'weighbridge',
        'toll_gate',
        'other_expenses',
        'supplier_name',
        'gross_weight',
        'net_weight',
        'tare_weight',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'quantity' => 'decimal:2',
        'loading' => 'decimal:2',
        'council_fee' => 'decimal:2',
        'weighbridge' => 'decimal:2',
        'toll_gate' => 'decimal:2',
        'other_expenses' => 'decimal:2',
        'gross_weight' => 'decimal:2',
        'net_weight' => 'decimal:2',
        'tare_weight' => 'decimal:2',
    ];

    public function equipment() {
        return $this->belongsTo(Equipment::class);
    }

    public function driver() {
        return $this->belongsTo(Employee::class, 'driver_id');
    }

    public function fuels() {
        return $this->hasMany(Fuel::class);
    }
}
