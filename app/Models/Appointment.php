<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $guarded=['id'];

    protected $fillable = [
        'doctor_id', 'appointment_date', 'start_time', 'end_time', 'name', 'email', 'phone',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class)->withTrashed();
    }

    public function getFormattedTimeAttribute()
    {
        $start = Carbon::parse($this->start_time)->format('h:i A');
        $end = Carbon::parse($this->end_time)->format('h:i A');

        return "$start - $end";
    }
}
