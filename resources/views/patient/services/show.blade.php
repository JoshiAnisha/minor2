@extends('patient.layouts.app')

@section('title', $service->name)

@section('content')
<div class="mb-3">
    <a href="{{ route('patient.services.index') }}" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Services</a>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        @if(!empty($service->category))
            <p class="small text-muted text-uppercase fw-semibold mb-1">{{ $service->category }}</p>
        @endif
        <h2 class="h5 fw-bold mb-2">{{ $service->name }}</h2>
        <div class="d-flex flex-wrap gap-2 mb-2">
            <span class="badge bg-light text-dark">{{ ucfirst($service->service_type ?? 'regular') }}</span>
            @if ($service->is_long_term ?? false)
                <span class="badge bg-info">Long-term</span>
            @endif
        </div>
        @if(!empty($service->details))
            <div class="text-muted small mb-3" style="white-space: pre-line;">{{ $service->details }}</div>
        @endif
        <p class="fw-bold text-primary mb-0">Rs {{ number_format((float) ($service->base_price ?? 0), 0) }}@if ($service->is_long_term ?? false)<span class="text-muted fw-normal small"> / day</span>@endif</p>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Request details</h5>
        @if ($service->is_long_term ?? false)
            <div class="alert alert-info py-2 mb-3 small">
                <strong>Long-term pricing:</strong> The price above is <strong>per day</strong>. Your total will be <strong>number of days × Rs {{ number_format((float) ($service->base_price ?? 0), 0) }}/day</strong>. Select your dates below to see the estimated total.
            </div>
        @endif
        <p class="text-muted small mb-3">Your request will be visible to all caregivers; they can accept at base price or place a bid.</p>
        @if (session('error'))
            <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success py-2 small">{{ session('success') }}</div>
        @endif

        <form action="{{ route('patient.services.book') }}" method="POST">
            @csrf
            <input type="hidden" name="service_id" value="{{ $service->id }}">
            <div class="row g-3">
                @if ($service->is_long_term ?? false)
                    <div class="col-12">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small fw-medium">Start date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" required min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-medium">End date <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" required min="{{ date('Y-m-d') }}" value="{{ old('end_date') }}">
                            </div>
                        </div>
                        <p id="longTermCalc" class="small text-primary mt-2 mb-0 d-none"><strong>Estimated total:</strong> <span id="calcDays">0</span> days × Rs <span id="calcRate">{{ number_format((float) ($service->base_price ?? 0), 0) }}</span>/day = Rs <span id="calcTotal">0</span></p>
                    </div>
                @else
                    <div class="col-md-6">
                        <label class="form-label small fw-medium">Date <span class="text-danger">*</span></label>
                        <input type="date" name="preferred_date" class="form-control form-control-sm" required min="{{ date('Y-m-d') }}" value="{{ old('preferred_date') }}" title="When you need care (one day)">
                    </div>
                @endif
                <div class="col-12">
                    <label class="form-label small fw-medium">Address</label>
                    <input type="text" name="location" class="form-control form-control-sm" required placeholder="Where care is needed">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-medium">Notes <span class="text-muted">(optional)</span></label>
                    <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="Any special requirements"></textarea>
                </div>
                <div class="col-12 pt-2">
                    <button type="submit" class="btn btn-primary">Submit request</button>
                </div>
            </div>
        </form>
    </div>
</div>
@if ($service->is_long_term ?? false)
<script>
(function() {
    var startEl = document.getElementById('start_date');
    var endEl = document.getElementById('end_date');
    var calcEl = document.getElementById('longTermCalc');
    var basePrice = {{ (float) ($service->base_price ?? 0) }};
    function updateCalc() {
        if (!startEl || !endEl || !calcEl || basePrice <= 0) return;
        var start = startEl.value ? new Date(startEl.value) : null;
        var end = endEl.value ? new Date(endEl.value) : null;
        if (!start || !end || end < start) {
            calcEl.classList.add('d-none');
            return;
        }
        var days = Math.round((end - start) / (24 * 60 * 60 * 1000)) + 1;
        if (days < 1) days = 1;
        var total = days * basePrice;
        document.getElementById('calcDays').textContent = days;
        document.getElementById('calcTotal').textContent = total.toLocaleString('en-IN', { maximumFractionDigits: 0 });
        calcEl.classList.remove('d-none');
    }
    if (startEl) startEl.addEventListener('change', updateCalc);
    if (endEl) endEl.addEventListener('change', updateCalc);
    if (startEl && endEl && startEl.value && endEl.value) updateCalc();
})();
</script>
@endif
@endsection
