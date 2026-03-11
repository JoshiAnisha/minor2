@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid px-0">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Create New Service</h3>
            </div>
            <div class="card-body">
                @if (!empty($presetCategory) || isset($presetLongTerm))
                    <div class="alert alert-info mb-4 d-flex align-items-center">
                        <i class="bi bi-info-circle me-2 fs-5"></i>
                        <div>
                            <strong>Selected from dropdown:</strong>
                            <span class="ms-1">{{ $presetCategory ?: 'Uncategorized' }}</span>
                            <span class="badge {{ (isset($presetLongTerm) && $presetLongTerm) ? 'bg-info' : 'bg-secondary' }} ms-2">
                                {{ (isset($presetLongTerm) && $presetLongTerm) ? 'Long-term' : 'Short-term' }}
                            </span>
                            <p class="mb-0 small mt-1 text-muted">Name and duration are pre-filled; fill in the rest below.</p>
                        </div>
                    </div>
                @endif

                @if (isset($errors) && $errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.services.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $presetCategory ?? '') }}" required placeholder="e.g. General Consultation, Physiotherapy Session">
                        @if(!empty($presetCategory))
                            <small class="form-text text-muted">Pre-filled from your selection; edit if you want a more specific name (e.g. General Check-up).</small>
                        @endif
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                            name="slug" value="{{ old('slug') }}" placeholder="Auto-generated from name if left empty">
                        <small class="form-text text-muted">Leave empty to auto-generate from service name</small>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="service_type" class="form-label">Service Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('service_type') is-invalid @enderror" id="service_type"
                            name="service_type" required>
                            <option value="">Select service type</option>
                            <option value="medical" {{ old('service_type') == 'medical' ? 'selected' : '' }}>Medical</option>
                            <option value="regular" {{ old('service_type') == 'regular' ? 'selected' : '' }}>Regular</option>
                        </select>
                        @error('service_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text"
                            class="form-control @error('category') is-invalid @enderror"
                            id="category"
                            name="category"
                            value="{{ old('category', $presetCategory ?? '') }}"
                            list="category-list"
                            placeholder="Choose existing or type a new category">
                        <datalist id="category-list">
                            @foreach ($existingCategories ?? [] as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                        <small class="form-text text-muted">Select from the list or type a new category name.</small>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        @if (isset($presetLongTerm))
                            <label class="form-label">Duration</label>
                            <p class="mb-0">
                                <span class="badge {{ $presetLongTerm ? 'bg-info' : 'bg-secondary' }} fs-6">{{ $presetLongTerm ? 'Long-term' : 'Short-term' }}</span>
                                <span class="text-muted small ms-2">(Set from your dropdown selection)</span>
                            </p>
                            <input type="hidden" name="is_long_term" value="{{ $presetLongTerm ? '1' : '0' }}">
                        @else
                            <label class="form-label">Duration</label>
                            @php
                                $isLongTermChecked = old('is_long_term') !== null ? (bool) old('is_long_term') : false;
                            @endphp
                            <input type="hidden" name="is_long_term" value="0">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_long_term" name="is_long_term" value="1" {{ $isLongTermChecked ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_long_term">Long-term service</label>
                            </div>
                            <small class="form-text text-muted d-block">Check for services booked over a period (e.g. nursing, elderly care). Leave unchecked for one-time visits.</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="base_price" class="form-label">Base Price <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('base_price') is-invalid @enderror" id="base_price"
                            name="base_price" value="{{ old('base_price') }}" step="0.01" min="0" required>
                        <small class="form-text text-muted">Enter price in decimal format (e.g., 100.50)</small>
                        @error('base_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="details" class="form-label">Details</label>
                        <textarea class="form-control @error('details') is-invalid @enderror" id="details" name="details" rows="5"
                            placeholder="Enter service details...">{{ old('details') }}</textarea>
                        @error('details')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Create Service</button>
                        <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
