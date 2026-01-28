@extends('admin.layouts.app')


@section('content')
<head>
  <meta charset="UTF-8" >
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Caregiver Management - Admin | SewaCare</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }

    .sidebar {
      height: 100vh;
      background-color: #fff;
      border-right: 1px solid #dee2e6;
    }

    .sidebar .nav-link {
      color: #333;
      padding: 12px;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background-color: #e9f7fc;
      color: #0dcaf0;
    }

    .card-summary {
      border-left: 5px solid #0dcaf0;
    }

    .logo-img {
      max-height: 50px;
    }

    .table-responsive {
      margin-top: 20px;
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-9 col-lg-10 p-4">
      <h2 class="mb-4">Caregiver Management</h2>
      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="card card-summary shadow-sm p-3">
            <h6>Total Caregivers</h6>
            <h4>{{ $totalCaregivers }}</h4>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-summary shadow-sm p-3">
            <h6>Active Caregivers</h6>
            <h4>{{ $activeCaregivers }}</h4>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-summary shadow-sm p-3">
            <h6>Pending Caregivers</h6>
            <h4>{{ $pendingCaregivers }}</h4>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>SN</th>
            <th>Name</th>
            <th>Type</th>
            <th>Qualification</th>
            <th>Experience</th>
            <th>Contact</th>
            <th>Skills</th>
            <th>Background Check</th>
            <th>Rating</th>
            <th>Field</th>
            <th>Address</th>
            <th>Bio</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($caregivers as $index => $caregiver)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $caregiver->user->name ?? 'N/A' }}</td>
            <td>{{ ucfirst($caregiver->caregiver_type) ?? 'N/A' }}</td>
            <td>{{ $caregiver->qualification ?? 'N/A' }}</td>
            <td>{{ $caregiver->experience ?? 'N/A' }}</td>
            <td>{{ $caregiver->contact_number ?? 'N/A' }}</td>
            <td>{{ $caregiver->skills ?? 'N/A' }}</td>

            <td>{{ $caregiver->background_check_status ? 'Passed' : 'Pending' }}</td>
            <td>{{ $caregiver->rating ?? 'N/A' }}</td>
            <td>{{ $caregiver->field ?? 'N/A' }}</td>
            <td>{{ $caregiver->address ?? 'N/A' }}</td>
            <td>{{ $caregiver->bio ?? 'N/A' }}</td>
            <td>
                @if($caregiver->availability_status)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-warning">Pending</span>
                @endif
            </td>
            <td>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.caregiver.edit', $caregiver->id) }}" class="btn btn-sm btn-info">Edit</a>
                    <form action="{{ route('admin.caregiver.status', $caregiver->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-sm btn-warning">
                            {{ $caregiver->availability_status ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.caregiver.destroy', $caregiver->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this caregiver?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="18" class="text-center">No caregivers found</td>
        </tr>
        @endforelse
    </tbody>
</table>

      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
@endsection
