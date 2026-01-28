@extends('admin.layouts.app')

@section('content')

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Appointments - Admin|SewaCare</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <style>
            .card-summary {
                border-left: 5px solid #0dcaf0;
            }
        </style>
    </head>

    <body>

        <div class="container-fluid">
            <div class="row">
                <!--Sidebar -->
                <div class="col-md-9 col-lg-10 p-4">
                    <h2 class="mb-4">Appointments Management</h2>
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card card-summary shadow-sm p-3">
                                <h6>Total Appointments</h6>
                                <h4>{{ $appointments->count() }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-summary shadow-sm p-3">
                                <h6>Pending</h6>
                                {{-- <h4>{{ $pending->count() }}</h4> --}}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-summary shadow-sm p-3">
                                <h6>Completed</h6>
                                {{-- <h4>{{ $completed }}</h4> --}}
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>SN</th>
                                    <th>Patient</th>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($appointments as $appointment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $appointment->name }}</td>
                                        <td>{{ $appointment->email }}</td>
                                        <td>{{ $appointment->date }}</td>
                                        <td>{{ $appointment->time }}</td>
                                        <td>
                                            <span class="badge bg-secondary">Pending</span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="#" class="btn btn-sm btn-info">Edit</a>

                                                <form action="#" method="POST"
                                                    onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            No appointments found
                                        </td>
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
