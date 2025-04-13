<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorTimeSlot extends Model
{
    protected $fillable = ['availability_id', 'start_time', 'end_time'];
}
