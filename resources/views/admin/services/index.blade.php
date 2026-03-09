@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid px-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Services</h3>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="newServiceDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Open new service
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="newServiceDropdown">
                    @foreach ($categoriesWithTermTypes ?? [] as $category => $termTypes)
                        <li><h6 class="dropdown-header">{{ $category }}</h6></li>
                        @if($termTypes['short'] ?? false)
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.services.create', ['category' => $category, 'is_long_term' => 0]) }}">
                                    <i class="bi bi-clock me-2"></i>Short-term
                                </a>
                            </li>
                        @endif
                        @if($termTypes['long'] ?? false)
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.services.create', ['category' => $category, 'is_long_term' => 1]) }}">
                                    <i class="bi bi-calendar-range me-2"></i>Long-term
                                </a>
                            </li>
                        @endif
                        @if(!$loop->last)
                            <li><hr class="dropdown-divider"></li>
                        @endif
                    @endforeach
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-muted" href="{{ route('admin.services.create') }}">
                            <i class="bi bi-three-dots me-2"></i>Others <span class="small">(Uncategorized)</span>
                        </a>
                    </li>
                    @if(empty($categoriesWithTermTypes))
                        <li><a class="dropdown-item" href="{{ route('admin.services.create') }}">Create new service</a></li>
                    @endif
                </ul>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (!isset($services) || $services->isEmpty())
            <div class="card shadow-sm">
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-bag-plus display-4"></i>
                    <p class="mb-2 mt-2">No services yet</p>
                    <p class="small mb-0">Run the seeder to add the 10 SewaCare services, or create one manually.</p>
                    <a href="{{ route('admin.services.create') }}" class="btn btn-primary mt-3">Create first service</a>
                </div>
            </div>
        @else
            @php
                $grouped = $services->groupBy(function ($s) { return $s->category ?: 'Uncategorized'; });
            @endphp
            @foreach ($grouped as $category => $items)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light py-2">
                        <h5 class="mb-0 small text-uppercase fw-semibold text-muted">{{ $category }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Base price</th>
                                        <th>Duration</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $service)
                                        <tr>
                                            <td><strong>{{ $service->name }}</strong></td>
                                            <td><span class="badge bg-secondary">{{ ucfirst($service->service_type) }}</span></td>
                                            <td>Rs {{ number_format($service->base_price, 0) }}</td>
                                            <td>
                                                @if ($service->is_long_term ?? false)
                                                    <span class="badge bg-info">Long-term</span>
                                                @else
                                                    <span class="badge bg-light text-dark">One-time / Short-term</span>
                                                @endif
                                            </td>
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
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
