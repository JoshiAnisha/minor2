@extends('Caregiver.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 main-content">
                <div class="card shadow-sm mb-4 mt-4">
                    <div class="card-body p-4">
                        <h3 class="text-center fw-bold mb-4">My Schedule</h3>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (isset($shiftTimes) && $shiftTimes->isNotEmpty())
                            <div class="mb-4">
                                <h5 class="fw-bold mb-3">Your saved availability</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Shift</th>
                                                <th>Time</th>
                                                <th>Day</th>
                                                <th>Date</th>
                                                <th>Service</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($shiftTimes as $st)
                                                <tr>
                                                    <td>{{ $st->shift }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($st->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($st->end_time)->format('g:i A') }}</td>
                                                    <td>{{ $st->day }}</td>
                                                    <td>{{ $st->available_date ? \Carbon\Carbon::parse($st->available_date)->format('M d, Y') : '—' }}</td>
                                                    <td>{{ $st->service }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <p class="text-muted small mb-0">Service requests that match your shift and date will appear in <strong>Service Requests</strong>.</p>
                            </div>
                        @else
                            <div class="alert alert-info mb-4">
                                <strong>No schedule yet.</strong> Add your availability below. Only service requests that match your shift and date will be shown to you for accept/reject.
                            </div>
                        @endif

                        <h5 class="fw-bold mb-3">Add availability</h5>
                        <form id="availabilityForm" method="POST" action="{{ route('caregiver.shift.store') }}">
                            @csrf

                            {{-- Shift --}}
                            <div class="mb-3">
                                <label class="form-label">Preferred Shift <span class="text-danger">*</span></label>
                                <div class="d-flex flex-wrap gap-3">
                                    <label class="form-check"><input type="radio" name="shift" value="Morning" class="form-check-input" required> Morning</label>
                                    <label class="form-check"><input type="radio" name="shift" value="Day" class="form-check-input"> Day</label>
                                    <label class="form-check"><input type="radio" name="shift" value="Night" class="form-check-input"> Night</label>
                                    <label class="form-check"><input type="radio" name="shift" value="Both" class="form-check-input"> Both</label>
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

                // Morning: 06:00 - 11:59
                if (selectedShift === 'Morning') {
                    if (startHour < 6 || startHour >= 12 || endHour < 6 || endHour > 12) {
                        alert('For Morning shift, time must be between 06:00 and 12:00.');
                        e.preventDefault();
                        return false;
                    }
                }
                // Day shift: 12:00 - 17:59
                if (selectedShift === 'Day') {
                    if (startHour < 12 || startHour >= 18 || endHour < 12 || endHour >= 18) {
                        alert('For Day shift, time must be between 12:00 and 18:00.');
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
