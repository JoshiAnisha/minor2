@extends('patient.layouts.app')

@section('content')
    <h1>Patient Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }}</p>
@endsection
