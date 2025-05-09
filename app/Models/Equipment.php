<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'equipments';

    protected $fillable = [
        'asset_code',
        'registration_number',
        'chassis_number',
        'engine_number',
        'type',
        'ownership',
        'equipment_name',
        'date_purchased',
        'value',
        'status',
        'pictures',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_purchased' => 'date',
        'value' => 'float',
        'pictures' => 'array', // JSON field
    ];

    public function trips() {
        return $this->hasMany(Trip::class);
    }

    public function spares() {
        return $this->hasMany(Spare::class);
    }

    public function equipmentInsurances() {
        return $this->hasMany(EquipmentInsurance::class);
    }

    public function latestInsurance() {
        return $this->hasOne(EquipmentInsurance::class)->latest('expiry_date');
    }

    public function equipmentTaxes() {
        return $this->hasMany(EquipmentTax::class);
    }

    public function machineryUsages() {
        return $this->hasMany(MachineryUsage::class);
    }

    public function vehicleLogs() {
        return $this->hasMany(VehicleLog::class);
    }
}
