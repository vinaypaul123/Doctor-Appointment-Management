<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $guarded=['id'];

    protected $fillable = [
        'doctor_id', 'appointment_date', 'start_time', 'end_time', 'name', 'email', 'phone',
    ];


}
