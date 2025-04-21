@extends('layouts.layoutuser')

@section('content')
<div class="container pt-4">
    <h2>Book Appointment with Dr. {{ $doctor->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('appointments.store', $doctor) }}">
        @csrf

        {{-- Flatpickr CSS --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

        <div class="form-group">
            <label for="appointment_date">Select Date</label>
            <input type="text" name="appointment_date" id="appointment_date" class="form-control" required>
        </div>

        <div class="row">
            <div class="col-4">
                <div id="time-slots-wrapper" class="mt-3" style="display:none;">
                    <label>Select Time Slot</label>
                    <div id="time-slots" class="row"></div>
                </div>
            </div>
        </div>

        <hr>

        <h5>Enter Your Details</h5>
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="tel" name="phone" class="form-control" required>
        </div>

        <input type="hidden" name="start_time" id="start_time">
        <input type="hidden" name="end_time" id="end_time">

        <button type="submit" class="btn btn-primary mt-3">Book Appointment</button>
    </form>
</div>

{{-- Flatpickr JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    const availabilities = @json($availabilities);
    const appointments = @json($appointments);

    // Get allowed dates with at least one slot
    const today = new Date();
    const allowedDates = [];

    for (let i = 0; i <= 30; i++) {
        const date = new Date();
        date.setDate(today.getDate() + i);
        const isoDate = date.toISOString().split('T')[0];
        const day = date.toLocaleString('en-US', { weekday: 'long' });

        if (availabilities[day] && availabilities[day].time_slots.length > 0) {
            allowedDates.push(isoDate);
        }
    }

    flatpickr("#appointment_date", {
        dateFormat: "Y-m-d",
        enable: allowedDates,
        onChange: function(selectedDates, dateStr) {
            document.getElementById('appointment_date').dispatchEvent(new Event('change'));
        }
    });

    function formatToAmPm(timeStr) {
        const [hours, minutes] = timeStr.split(':');
        const date = new Date();
        date.setHours(parseInt(hours), parseInt(minutes));
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
    }

    document.getElementById('appointment_date').addEventListener('change', function () {
        const selectedDate = new Date(this.value);
        const day = selectedDate.toLocaleString('en-US', { weekday: 'long' });

        const slotContainer = document.getElementById('time-slots');
        slotContainer.innerHTML = '';
        document.getElementById('time-slots-wrapper').style.display = 'none';
        document.getElementById('start_time').value = '';
        document.getElementById('end_time').value = '';

        if (availabilities[day]) {
            const slots = availabilities[day].time_slots;
            const bookedSlots = appointments.filter(appointment => appointment.appointment_date === this.value);

            if (slots.length > 0) {
                document.getElementById('time-slots-wrapper').style.display = 'block';
                slots.forEach(slot => {
                    const slotBooked = bookedSlots.some(appointment =>
                        appointment.start_time === slot.start_time &&
                        appointment.end_time === slot.end_time
                    );

                    const displayText = `${formatToAmPm(slot.start_time)} - ${formatToAmPm(slot.end_time)}`;

                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = `btn m-1 ${slotBooked ? 'btn-outline-secondary disabled' : 'btn-outline-primary'}`;
                    btn.textContent = slotBooked ? `${displayText} (Booked)` : displayText;

                    if (!slotBooked) {
                        btn.onclick = () => {
                            document.querySelectorAll('#time-slots button').forEach(b => b.classList.remove('active'));
                            btn.classList.add('active');
                            document.getElementById('start_time').value = slot.start_time;
                            document.getElementById('end_time').value = slot.end_time;
                        };
                    }

                    slotContainer.appendChild(btn);
                });
            }
        }
    });
</script>
@endsection
