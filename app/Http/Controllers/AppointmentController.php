<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{

    public function index()
    {
        $doctors = Doctor::all();
        return view('appointments.index', compact('doctors'));

    }
    public function create(Doctor $doctor)
    {
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $availabilities = $doctor->availabilities()->where('status', 'working')->with('timeSlots')->get()->keyBy('day') ?? [];

        // Fetch existing appointments for the selected doctor
        $appointments = Appointment::where('doctor_id', $doctor->id)
                                   ->whereDate('appointment_date', '>=', now()->toDateString()) // Optional: Filter by future dates
                                   ->get();

        return view('appointments.create', compact('doctor', 'availabilities', 'daysOfWeek', 'appointments'));
    }

    public function store(Request $request, Doctor $doctor)
    {
        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        // Check if doctor is available that day
        $day = Carbon::parse($request->appointment_date)->format('l'); // e.g., Monday
        $availability = $doctor->availabilities()->where('day', $day)->where('status', 'working')->first();
        if (!$availability) {
            return back()->with('error', 'Doctor is not available on the selected date.');
        }

        // Check if time slot is already booked
        $alreadyBooked = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $request->appointment_date)
            ->where('start_time', $request->start_time)
            ->where('end_time', $request->end_time)
            ->exists();

        if ($alreadyBooked) {
            return back()->with('error', 'This time slot is already booked.');
        }

        // Store appointment
        Appointment::create([
            'doctor_id' => $doctor->id,
            'appointment_date' => $request->appointment_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('appointments.create', $doctor)->with('success', 'Appointment booked successfully.');
    }
}
