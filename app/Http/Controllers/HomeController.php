<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Availability;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $doctors = Doctor::with('availabilities.timeSlots')->get();
        $doctorCount = Doctor::count();
        $appointmentCount = Appointment::count();

        return view('admin.doctors.dashborad' , compact('doctors', 'doctorCount', 'appointmentCount'));
    }
}
