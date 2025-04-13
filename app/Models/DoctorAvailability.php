<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAvailability extends Model
{
    protected $fillable = ['doctor_id', 'day'];

    public function timeSlots() {
        return $this->hasMany(DoctorTimeSlot::class, 'availability_id');
    }
}
