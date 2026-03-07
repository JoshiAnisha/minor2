@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid px-0">
        <div class="mb-3">
            <a href="{{ route('admin.services.index') }}" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left me-1"></i> Back to services</a>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Edit Service: {{ $service->name }}</h3>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.services.update', $service) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $service->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                            name="slug" value="{{ old('slug', $service->slug) }}" placeholder="Auto-generated from name if left empty">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="service_type" class="form-label">Service Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('service_type') is-invalid @enderror" id="service_type"
                            name="service_type" required>
                            <option value="medical" {{ old('service_type', $service->service_type) == 'medical' ? 'selected' : '' }}>Medical</option>
                            <option value="regular" {{ old('service_type', $service->service_type) == 'regular' ? 'selected' : '' }}>Regular</option>
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
                            value="{{ old('category', $service->category) }}"
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

                    <div class="mb-3 form-check">
                        <input type="hidden" name="is_long_term" value="0">
                        <input type="checkbox" class="form-check-input" id="is_long_term" name="is_long_term" value="1" {{ old('is_long_term', $service->is_long_term) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_long_term">Available for long-term booking</label>
                    </div>

                    <div class="mb-3">
                        <label for="base_price" class="form-label">Base Price <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('base_price') is-invalid @enderror" id="base_price"
                            name="base_price" value="{{ old('base_price', $service->base_price) }}" step="0.01" min="0" required>
                        @error('base_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="details" class="form-label">Details</label>
                        <textarea class="form-control @error('details') is-invalid @enderror" id="details" name="details" rows="5">{{ old('details', $service->details) }}</textarea>
                        @error('details')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Service</button>
                        <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
