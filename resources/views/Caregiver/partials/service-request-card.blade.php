<div class="col-md-6 col-lg-4">
    <div class="card request-card shadow-sm h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ $serviceRequest->service->name ?? 'Service' }}</span>
            <span class="badge bg-light text-dark small">Rs {{ number_format($serviceRequest->effective_base_price ?? 0, 0) }}</span>
        </div>
        <div class="card-body">
            <div class="info-row">
                <i class="bi bi-person"></i>
                <span><strong>Patient:</strong>
                    @if($serviceRequest->patient)
                        <a href="{{ route('caregiver.patient.show', $serviceRequest->patient) }}" class="text-decoration-none">{{ optional($serviceRequest->patient->user)->name ?? optional($serviceRequest->user)->name ?? 'N/A' }}</a>
                    @else
                        {{ optional($serviceRequest->user)->name ?? 'N/A' }}
                    @endif
                </span>
            </div>
            <div class="info-row">
                <i class="bi bi-geo-alt"></i>
                <span>{{ $serviceRequest->location }}</span>
            </div>
            <div class="info-row">
                <i class="bi bi-calendar-event"></i>
                <span>
                    @if ($serviceRequest->isLongTerm() && $serviceRequest->start_date && $serviceRequest->end_date)
                        {{ $serviceRequest->start_date->format('d M Y') }} – {{ $serviceRequest->end_date->format('d M Y') }}
                    @else
                        {{ $serviceRequest->preferred_time ? $serviceRequest->preferred_time->format('d M Y, h:i A') : '-' }}
                    @endif
                </span>
            </div>
            @if ($serviceRequest->description)
                <div class="info-row">
                    <i class="bi bi-chat-text"></i>
                    <span class="small text-muted">{{ Str::limit($serviceRequest->description, 120) }}</span>
                </div>
            @endif

            <hr class="my-2">

            @if($serviceRequest->bids->isNotEmpty())
                <div class="alert alert-secondary py-1 mb-1 small">
                    <strong>Bidding closed.</strong> Patient will choose from existing offers.
                </div>
            @else
                <p class="small text-muted mb-1">Choose one:</p>
                <div class="d-flex flex-wrap gap-1 align-items-center mb-1">
                    <form method="POST" action="{{ route('caregiver.service.acceptBase', $serviceRequest->id) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-accept btn-sm">Accept at base price</button>
                    </form>
                    @php $basePrice = (float) ($serviceRequest->effective_base_price ?? 0); @endphp
                    <form method="POST" action="{{ route('caregiver.service.placeBid') }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="service_request_id" value="{{ $serviceRequest->id }}">
                        <div class="d-inline-flex align-items-center gap-1">
                            <div class="input-group input-group-sm" style="max-width: 140px;">
                                <span class="input-group-text text-muted" style="font-size: 0.75rem;">Rs</span>
                                <input type="number" name="proposed_price" class="form-control form-control-sm" required placeholder="e.g. 450" step="0.01" min="0" value="{{ old('proposed_price', (int)$basePrice) }}" title="Your total bid amount">
                                <button type="submit" class="btn btn-warning btn-sm">Bid</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
            <form method="POST" action="{{ route('caregiver.service.reject', $serviceRequest->id) }}" class="d-inline" onsubmit="return confirm('Decline this request?');">
                @csrf
                <button type="submit" class="btn btn-link btn-sm text-muted p-0" style="font-size: 0.8rem;">Not interested</button>
            </form>
        </div>
    </div>
</div>
