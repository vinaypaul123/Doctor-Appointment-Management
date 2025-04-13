<?php

namespace App\Http\Controllers;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Availability;
use App\Models\DoctorAvailability;
use App\Models\DoctorTimeSlot;

use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index() {
        $doctors = Doctor::with('availabilities.timeSlots')
                         ->withCount('appointments') // This is key
                         ->get();

        $doctorCount = Doctor::count();
        $appointmentCount = Appointment::count();

        return view('admin.doctors.dashborad', compact('doctors', 'doctorCount', 'appointmentCount'));
    }

    public function create() {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return view('admin.doctors.create', compact('days'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'specialization' => 'required',
            'qualification' => 'nullable',
            'description' => 'nullable',
            'availability' => 'required|array',
        ]);

        $doctor = Doctor::create($request->only('name', 'specialization', 'qualification', 'description'));

        foreach ($request->availability as $day => $data) {
            // Determine status: default to 'leave' if not explicitly set to 'working'
            $status = isset($data['status']) && $data['status'] === 'working' ? 'working' : 'leave';
            // Create availability for the day
            $availability = $doctor->availabilities()->create([
                'day' => $day,
                'status' => $status,
            ]);

            // If working, process time slots
            if (isset($data['slots']) && is_array($data['slots'])) {
                foreach ($data['slots'] as $slot) {
                    if (!empty($slot['start']) && !empty($slot['end'])) {
                        $availability->timeSlots()->create([
                            'start_time' => $slot['start'],
                            'end_time' => $slot['end'],
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor added');
    }



    public function edit(Doctor $doctor)
    {
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required',
            'specialization' => 'required',
            'qualification' => 'nullable',
            'description' => 'nullable',
            'availability' => 'required|array',
        ]);

        // Update doctor details
        $doctor->update($request->only('name', 'specialization', 'qualification', 'description'));

        foreach ($request->availability as $day => $data) {
            $status = isset($data['status']) && $data['status'] === 'working' ? 'working' : 'leave';

            // Get availability for the day or create if not exists
            $availability = $doctor->availabilities()->firstOrNew(['day' => $day]);

            // Update the status
            $availability->status = $status;
            $availability->save();

            if ( isset($data['slots']) && is_array($data['slots'])) {
                $existingSlots = $availability->timeSlots->keyBy('id');

                foreach ($data['slots'] as $index => $slot) {
                    if (isset($slot['slot_id'])) {
                        // Update existing slot
                        $timeSlot = $existingSlots->get($slot['slot_id']);
                        if ($timeSlot) {
                            $timeSlot->update([
                                'start_time' => $slot['start'],
                                'end_time' => $slot['end'],
                            ]);
                            $existingSlots->forget($slot['slot_id']);
                        }
                    } else {
                        // Create new slot
                        if (!empty($slot['start']) && !empty($slot['end'])) {
                            $availability->timeSlots()->create([
                                'start_time' => $slot['start'],
                                'end_time' => $slot['end'],
                            ]);
                        }
                    }
                }

                // Delete removed slots
                foreach ($existingSlots as $slot) {
                    $slot->delete();
                }
            } else {
                // Leave day — remove all existing slots
                $availability->timeSlots()->delete();
            }
        }

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated successfully');
    }





    public function destroy(Doctor $doctor) {
        $doctor->delete();
        return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted');
    }
}
