@extends('layouts.home_layout')
@section('content')

<div class="container">
    <h2>Edit Doctor</h2>

    <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $doctor->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Specialization</label>
            <input type="text" name="specialization" value="{{ old('specialization', $doctor->specialization) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Qualification</label>
            <textarea name="qualification" class="form-control">{{ old('qualification', $doctor->qualification) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $doctor->description) }}</textarea>
        </div>

        <hr>
        <h4>Availability</h4>

        @php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        @endphp

@foreach ($days as $day)
@php
    $availability = $doctor->availabilities->where('day', $day)->first();
    $selectedStatus = old("availability.$day.status", $availability->status ?? 'working');
    $isWorking = $selectedStatus == 'working';
    $slots = $availability ? $availability->timeSlots : collect();
@endphp

<div class="mb-3 border p-3 rounded">
    <div class="d-flex justify-content-between align-items-center">
        <label><strong>{{ $day }}</strong></label>
        <div class="form-group">
            <label>Status</label>
            <select name="availability[{{ $day }}][status]" class="form-control status-dropdown" data-day="{{ $day }}">
                <option value="working" {{ $selectedStatus == 'working' ? 'selected' : '' }}>Working</option>
                <option value="leave" {{ $selectedStatus == 'leave' ? 'selected' : '' }}>Leave</option>
            </select>
        </div>
    </div>

    <div class="time-slots-container mt-2" data-day="{{ $day }}" >
        @foreach ($slots as $index => $slot)
            <div class="row time-slot mb-2">
                <input type="hidden" name="availability[{{ $day }}][slots][{{ $index }}][slot_id]" value="{{ $slot->id }}">
                <div class="col">
                    <input type="time" name="availability[{{ $day }}][slots][{{ $index }}][start]" class="form-control" value="{{ $slot->start_time }}" required>
                </div>
                <div class="col">
                    <input type="time" name="availability[{{ $day }}][slots][{{ $index }}][end]" class="form-control" value="{{ $slot->end_time }}" required>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger remove-slot">×</button>
                </div>
            </div>
        @endforeach
    </div>

    <button type="button" class="btn btn-secondary add-slot" data-day="{{ $day }}" >Add Time Slot</button>
</div>
@endforeach

        <button type="submit" class="btn btn-primary">Update Doctor</button>
    </form>
</div>

<script>
    // Add time slot
    document.querySelectorAll('.add-slot').forEach(button => {
        button.addEventListener('click', () => {
            const day = button.getAttribute('data-day');
            const container = document.querySelector(`.time-slots-container[data-day="${day}"]`);
            const index = container.querySelectorAll('.time-slot').length;

            const slot = document.createElement('div');
            slot.className = 'row time-slot mb-2';
            slot.innerHTML = `
                <div class="col">
                    <input type="time" name="availability[${day}][slots][${index}][start]" class="form-control" required>
                </div>
                <div class="col">
                    <input type="time" name="availability[${day}][slots][${index}][end]" class="form-control" required>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger remove-slot">×</button>
                </div>
            `;
            container.appendChild(slot);
        });
    });

    // Remove slot
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-slot')) {
            e.target.closest('.time-slot').remove();
        }
    });

    // Toggle working/leave
    // document.querySelectorAll('.working-day-checkbox').forEach(checkbox => {
    //     const day = checkbox.getAttribute('data-day');
    //     const container = document.querySelector(`.time-slots-container[data-day="${day}"]`);
    //     const button = document.querySelector(`.add-slot[data-day="${day}"]`);

    //     function toggleSlots() {
    //         if (checkbox.checked) {
    //             container.style.display = 'block';
    //             button.style.display = 'inline-block';
    //         } else {
    //             container.innerHTML = '';
    //             container.style.display = 'none';
    //             button.style.display = 'none';
    //         }
    //     }

    //     checkbox.addEventListener('change', toggleSlots);
    //     toggleSlots(); // initialize
    // });
</script>
@endsection
