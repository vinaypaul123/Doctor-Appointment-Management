<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks to prevent constraint issues during seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('doctor_time_slots')->truncate();
        DB::table('doctor_availabilities')->truncate();
        DB::table('doctors')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Common time slots for all working days
        $timeSlots = [
            ['start_time' => '09:00:00', 'end_time' => '09:30:00'],
            ['start_time' => '09:30:00', 'end_time' => '10:00:00'],
            ['start_time' => '10:00:00', 'end_time' => '10:30:00'],
            ['start_time' => '10:30:00', 'end_time' => '11:00:00'],
        ];

        // Doctor 1
        $doctor1 = \App\Models\Doctor::create([
            'name' => 'Dr. Alice Smith',
            'specialization' => 'Cardiologist',
            'qualification' => 'MBBS, MD',
            'description' => 'Expert in cardiovascular health.',
        ]);

        // Doctor 2
        $doctor2 = \App\Models\Doctor::create([
            'name' => 'Dr. John Doe',
            'specialization' => 'Dermatologist',
            'qualification' => 'MBBS, DDVL',
            'description' => 'Skin care and treatment specialist.',
        ]);

        // Days of the week
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        // Assign availabilities and time slots for each doctor
        foreach ([$doctor1, $doctor2] as $doctor) {
            foreach ($days as $day) {
                $availability = DB::table('doctor_availabilities')->insertGetId([
                    'doctor_id' => $doctor->id,
                    'day' => $day,
                    'status' => 'working',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($timeSlots as $slot) {
                    DB::table('doctor_time_slots')->insert([
                        'availability_id' => $availability,
                        'start_time' => $slot['start_time'],
                        'end_time' => $slot['end_time'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
