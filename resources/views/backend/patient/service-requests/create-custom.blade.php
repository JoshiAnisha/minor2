@extends('backend.layouts.dashboard.app')

@section('title', 'Custom Request')

@section('content')
<div class="mb-3">
    <a href="{{ route('patient.services.index') }}" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Back to Services</a>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h2 class="h5 fw-bold mb-1">Custom service request</h2>
        <p class="text-muted small mb-0">Describe what you need. Your request will be visible to all caregivers so they can respond with an offer.</p>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        @if (session('error'))
            <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger py-2 small">
                @foreach ($errors->all() as $e)
                    <span class="d-block">{{ $e }}</span>
                @endforeach
            </div>
        @endif

        <form action="{{ route('patient.services.book') }}" method="POST" id="customRequestForm">
            @csrf
            <input type="hidden" name="service_id" value="{{ $service->id }}">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-medium">What do you need?</label>
                    <textarea name="description" class="form-control form-control-sm @error('description') is-invalid @enderror" rows="3" required placeholder="e.g. Post-surgery care, 2 hours daily for one week">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label small fw-medium">Duration <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <label class="form-check">
                            <input type="radio" name="duration" value="one_day" class="form-check-input" {{ old('duration', 'one_day') === 'one_day' ? 'checked' : '' }}> One day
                        </label>
                        <label class="form-check">
                            <input type="radio" name="duration" value="long_term" class="form-check-input" {{ old('duration') === 'long_term' ? 'checked' : '' }}> Multiple days (long-term)
                        </label>
                    </div>
                </div>
                <div id="oneDayDate" class="col-md-6">
                    <label class="form-label small fw-medium">Date <span class="text-danger">*</span></label>
                    <input type="date" name="preferred_date" id="preferred_date" class="form-control form-control-sm" min="{{ date('Y-m-d') }}" value="{{ old('preferred_date') }}">
                </div>
                <div id="longTermDates" class="col-12 d-none">
                    <div class="alert alert-info py-2 mb-2 small">
                        <strong>Multiple days:</strong> For long-term requests, the total price is usually calculated as <strong>number of days × per-day rate</strong>. Caregivers will see your dates and can quote a total price.
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Start date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">End date <span class="text-danger">*</span></label>
                            <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" min="{{ date('Y-m-d') }}" value="{{ old('end_date') }}">
                        </div>
                    </div>
                    <p id="customLongTermCalc" class="small text-muted mt-2 mb-0 d-none"><strong>Duration:</strong> <span id="customCalcDays">0</span> days selected. Caregivers will quote based on this period.</p>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-medium">Address</label>
                    <input type="text" name="location" class="form-control form-control-sm" required placeholder="Where care is needed" value="{{ old('location') }}">
                </div>
                <div class="col-12 pt-2">
                    <button type="submit" class="btn btn-primary">Submit request</button>
                </div>
            </div>
        </form>
        <script>
            (function() {
                var oneDay = document.querySelector('input[name="duration"][value="one_day"]');
                var longTerm = document.querySelector('input[name="duration"][value="long_term"]');
                var oneDayDate = document.getElementById('oneDayDate');
                var longTermDates = document.getElementById('longTermDates');
                var preferredDate = document.getElementById('preferred_date');
                var startDate = document.getElementById('start_date');
                var endDate = document.getElementById('end_date');
                var calcEl = document.getElementById('customLongTermCalc');
                var daysEl = document.getElementById('customCalcDays');
                function toggle() {
                    var isLong = longTerm && longTerm.checked;
                    if (oneDayDate) oneDayDate.classList.toggle('d-none', isLong);
                    if (longTermDates) longTermDates.classList.toggle('d-none', !isLong);
                    if (preferredDate) preferredDate.required = !isLong;
                    if (startDate) startDate.required = isLong;
                    if (endDate) endDate.required = isLong;
                    if (!isLong && calcEl) calcEl.classList.add('d-none');
                }
                function updateCustomDays() {
                    if (!calcEl || !daysEl || !startDate || !endDate || !startDate.value || !endDate.value) {
                        if (calcEl) calcEl.classList.add('d-none');
                        return;
                    }
                    var start = new Date(startDate.value);
                    var end = new Date(endDate.value);
                    var days = Math.round((end - start) / (24 * 60 * 60 * 1000)) + 1;
                    if (days < 1) days = 1;
                    daysEl.textContent = days;
                    calcEl.classList.remove('d-none');
                }
                if (startDate) startDate.addEventListener('change', updateCustomDays);
                if (endDate) endDate.addEventListener('change', updateCustomDays);
                if (oneDay) oneDay.addEventListener('change', toggle);
                if (longTerm) longTerm.addEventListener('change', toggle);
                toggle();
                updateCustomDays();
            })();
        </script>
    </div>
</div>
@endsection
