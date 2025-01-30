@extends('layout')
@section('main-content')
    <form action="{{ isset($data) ? route('update') : route('store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="key" value="{{ isset($key) ? $key : ''}}" />
        <!-- User Credentials  -->
        <fieldset class="border p-3 mb-4">
            <legend class="mb-3 azm-color-444">{{ isset($data) ? 'Edit' : 'Create' }} Detail</legend>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label azm-color-666">Name:</label>
                        <input type="text" id="name" class="form-control" placeholder="Enter a Name..."
                            name="name" value="{{ $data['name'] ?? '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label azm-color-666">Password:</label>
                        <input type="password" id="password" class="form-control" placeholder="Enter a Password..."
                            name="password" value="{{ $data['password'] ?? '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label azm-color-666">Email:</label>
                        <input type="email" id="email" class="form-control" placeholder="Enter an Email address..."
                            name="email" value="{{ $data['email'] ?? '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="image" class="form-label azm-color-666">Image:</label>
                        <input type="file" id="image" class="form-control" placeholder="Choose Image" name="image">
                    </div>
                    @if (isset($data))
                        <input type="hidden" name="image" value="{{ $data['image'] ?? '' }}">
                        <img src="{{ asset('storage') }}/ {{ $data['image'] ?? '' }}" alt="#" width="100px"
                            height="100px">
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="mobile" class="form-label azm-color-666">Mobile:</label>
                        <input type="text" id="mobile" class="form-control" placeholder="Enter a Mobile..."
                            name="mobile" value="{{ $data['mobile'] ?? '' }}" maxlength="10">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date" class="form-label azm-color-666">Date:</label>
                        <input type="date" id="date" class="form-control" name="date"
                            value="{{ $data['date'] ?? '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date" class="form-label azm-color-666">Role:</label>
                        <select class="form-control" name="role">
                            <option value="">Select Role</option>
                            <option value="admin" {{ isset($data) && $data['role'] == 'admin' ? 'selected' : '' }}>Admin
                            </option>
                            <option value="user" {{ isset($data) && $data['role'] == 'user' ? 'selected' : '' }}>User
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </fieldset>
        <!-- Submit Button  -->
        <button type="submit" class="btn btn-primary btn-lg azm-btn-primary"><i class="bi bi-envelope"
                aria-hidden="true"></i> {{ isset($data) ? 'Update' : 'Submit' }}</button>
    </form>
@endsection
