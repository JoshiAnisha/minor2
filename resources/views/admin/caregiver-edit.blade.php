@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid px-0">
        <h3>Edit Caregiver</h3>

        <form action="{{ route('admin.caregivers.update', $caregiver) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Skills</label>
                <input type="text" name="skills" class="form-control" value="{{ $caregiver->skills }}">
            </div>

            <div class="mb-3">
                <label>Field</label>
                <input type="text" name="field" class="form-control" value="{{ $caregiver->field }}">
            </div>

            <div class="mb-3">
                <label>Address</label>
                <input type="text" name="address" class="form-control" value="{{ $caregiver->address }}">
            </div>

            <div class="mb-3">
                <label>Bio</label>
                <textarea name="bio" class="form-control">{{ $caregiver->bio }}</textarea>
            </div>

            <button class="btn btn-success">Update</button>
            <a href="{{ route('admin.caregivers.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection
