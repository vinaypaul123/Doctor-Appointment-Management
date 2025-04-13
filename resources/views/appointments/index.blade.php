@extends('layouts.layoutuser')

@section('content')
<div class="container pt-4">
    <h2 class="mb-4">Available Doctors</h2>

    <div class="row">
        @foreach($doctors as $doctor)
            <div class="col-md-4 mb-4 ">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $doctor->name }}</h5>
                        <p class="card-text">
                            <strong>Specialization:</strong> {{ $doctor->specialization ?? 'N/A' }} <br>
                        </p>
                        <a href="{{ route('appointments.create', $doctor) }}" class="btn btn-primary">Book Appointment</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
