<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\DoctorDto;
use App\Filters\DescOrderFilter;
use App\Http\Requests\Doctor\StoreRequest;
use App\Http\Requests\Doctor\UpdateRequest;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Availability;
use App\Models\DoctorAvailability;
use App\Models\DoctorTimeSlot;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;

class DoctorController extends Controller
{
    public function __construct(protected AdminService $doctor_service)
    {

    }

    public function index() {
        $doctors=$this->doctor_service->list([DescOrderFilter::class])
            ->withCount('appointments')
            ->with('availabilities.timeSlots')
            ->get();

        $doctorCount = Doctor::count();
        $appointmentCount = Appointment::count();

        return view('admin.doctors.dashborad', compact('doctors', 'doctorCount', 'appointmentCount'));
    }

    public function create() {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return view('admin.doctors.create', compact('days'));
    }

    public function store(StoreRequest $request)
    {
        $dto=DoctorDto::build($request->validated());
        $doctor = $this->doctor_service->create($dto);
        foreach ($request->availability as $day => $data) {
            $this->doctor_service->createAvailabilitytest($doctor,$day,$data);
        }

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor added');
    }


    public function edit(Doctor $doctor)
    {
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(UpdateRequest $request, int $id)
    {
        $dto=DoctorDto::build($request->validated());

        $doctor=$this->doctor_service->update($id,$dto);

        foreach ($request->availability as $day => $data) {
                $this->doctor_service->updateAvailability($doctor,$day,$data);
        }

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated successfully');
    }


    public function destroy(int $id) {
        $this->doctor_service->delete($id);
        return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted');
    }
}
