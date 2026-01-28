@extends('Caregiver.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 main-content">
                <div class="card shadow-sm mb-4 mt-4">
                    <div class="card-body p-4">
                        <h3 class="text-center fw-bold mb-4">My Available Shift and Time</h3>

                        <form id="availabilityForm" method="POST" action="{{ route('caregiver.shift-time.store') }}">
                            @csrf

                            {{-- Shift --}}
                            <div class="mb-3">
                                <label class="form-label">Preferred Shift</label>
                                <div class="btn-group w-100">
                                    <input type="radio" name="shift" value="Day" required> Day
                                    <input type="radio" name="shift" value="Night" required> Night
                                    <input type="radio" name="shift" value="Both" required> Both
                                </div>
                            </div>

                            {{-- Time --}}
                            <div class="mb-3">
                                <label class="form-label">Available Time</label>
                                <div class="row">
                                    <div class="col">
                                        <input type="time" name="start_time" id="start_time" class="form-control"
                                            required>
                                    </div>
                                    <div class="col">
                                        <input type="time" name="end_time" id="end_time" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Day --}}
                            <div class="mb-3">
                                <label class="form-label">Available Day</label>
                                <select name="day" class="form-select" required>
                                    <option value="" disabled selected>Select Day</option>
                                    @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                        <option value="{{ $day }}">{{ $day }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Service --}}
                            <div class="mb-3">
                                <label class="form-label">Service</label>
                                <select name="service" class="form-select" required>
                                    <option value="" disabled selected>Select Service</option>
                                    <option>Home Nursing</option>
                                    <option>Physiotherapy</option>
                                    <option>Child Care Support</option>
                                    <option>Post-Surgery Care</option>
                                </select>
                            </div>

                            {{-- Date --}}
                            <div class="mb-4">
                                <label class="form-label">Available Date</label>
                                <input type="date" name="available_date" id="available_date" class="form-control"
                                    required>
                            </div>

                            <button type="submit" class="btn btn-info w-100 text-white">
                                Save Availability
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Frontend Validation JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('availabilityForm');
            const shiftRadios = document.querySelectorAll('input[name="shift"]');
            const startTime = document.getElementById('start_time');
            const endTime = document.getElementById('end_time');
            const availableDate = document.getElementById('available_date');

            // Set min date to today
            const today = new Date().toISOString().split('T')[0];
            availableDate.setAttribute('min', today);

            form.addEventListener('submit', function(e) {
                const selectedShift = document.querySelector('input[name="shift"]:checked').value;

                const start = startTime.value;
                const end = endTime.value;

                if (!start || !end) return; // let HTML5 handle required

                const startHour = parseInt(start.split(':')[0]);
                const endHour = parseInt(end.split(':')[0]);

                // Day shift: 06:00 - 17:59
                if (selectedShift === 'Day') {
                    if (startHour < 6 || startHour >= 18 || endHour < 6 || endHour >= 18) {
                        alert('For Day shift, time must be between 06:00 and 18:00.');
                        e.preventDefault();
                        return false;
                    }
                }

                // Night shift: 18:00 - 05:59
                if (selectedShift === 'Night') {
                    if (!((startHour >= 18 || startHour < 6) && (endHour > 18 || endHour <= 6))) {
                        alert('For Night shift, time must be between 18:00 and 06:00.');
                        e.preventDefault();
                        return false;
                    }
                }

                // Date validation is handled by min attribute
            });
        });
    </script>
@endsection
