<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use SoftDeletes;

    protected $guarded=['id'];

    protected $fillable = ['name', 'specialization', 'qualification', 'description'];

    public function availabilities() {
        return $this->hasMany(DoctorAvailability::class);
    }

    public function appointments() {
        return $this->hasMany(Appointment::class);
    }

}
