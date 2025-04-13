@extends('layouts.home_layout')
@section('content')

<div class="container">
    {{-- Actions --}}



    <table class="table">
        <thead>
            <tr>
                <th>Pateint Name</th>
                <th>Email</th>
                <th>phone</th>
                <th>Booked Time</th>
                <th>Doctor</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($appointment as $appointments)
                <tr>
                    <td>{{ $appointments->name }}</td>
                    <td>{{ $appointments->email }}</td>
                    <td>{{ $appointments->phone }}</td>
                    <td>{{ $appointments->formatted_time  }}</td>
                    <td>{{ $appointments->doctor->name  }}</td>


                </tr>
            @endforeach
        </tbody>
    </table>


</div>


@endsection
