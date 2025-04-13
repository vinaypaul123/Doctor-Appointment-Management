@extends('layouts.home_layout')
@section('content')

<div class="container">
    <h1 class="mb-4">Welcome, Admin!</h1>

    <div class="row">
        {{-- Quick Stats or Actions --}}
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Doctors</h5>
                    <p class="card-text">{{ $doctorCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Appointments</h5>
                    <p class="card-text">{{ $appointmentCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <a href="{{ route('admin.doctors.create') }}" class="btn btn-success mb-3">Add Doctor</a>
    @if ($doctors->count() > 0)

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Specialization</th>
                <th>Qualification</th>
                <th>Appointments</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($doctors as $doctor)
                <tr>
                    <td>{{ $doctor->name }}</td>
                    <td>{{ $doctor->specialization }}</td>
                    <td>{{ $doctor->qualification }}</td>
                    <td>{{ $doctor->appointments_count }}</td>
                    <td>
                        <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="{{ route('admin.doctors.destroy', $doctor->id) }}" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this doctor?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

</div>


@endsection
