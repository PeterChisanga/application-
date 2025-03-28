<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fuel extends Model {
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'machinery_usage_id',
        'litres_added',
        'cost',
        'refuel_location',
    ];

    protected $casts = [
        'litres_added' => 'decimal:2',
        'cost' => 'decimal:2',
    ];

    public function trip() {
        return $this->belongsTo(Trip::class);
    }

    public function machineryUsage() {
        return $this->belongsTo(MachineryUsage::class);
    }
}
