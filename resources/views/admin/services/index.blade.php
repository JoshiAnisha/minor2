@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Services</h3>
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary">Open new service</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-0">
                @if (!isset($services) || $services->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-bag-plus display-4"></i>
                        <p class="mb-2 mt-2">No services yet</p>
                        <p class="small mb-0">Services you create here will appear on patient and caregiver dashboards.</p>
                        <a href="{{ route('admin.services.create') }}" class="btn btn-primary mt-3">Create first service</a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Base price</th>
                                    <th>Slug</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($services as $service)
                                    <tr>
                                        <td><strong>{{ $service->name }}</strong></td>
                                        <td><span class="badge bg-secondary">{{ ucfirst($service->service_type) }}</span></td>
                                        <td>{{ number_format($service->base_price, 2) }}</td>
                                        <td><code class="small">{{ $service->slug }}</code></td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.services.show', $service) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                            <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this service?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
