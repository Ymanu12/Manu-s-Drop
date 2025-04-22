@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Settings') }}</div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- Navigation Buttons -->
                    <div class="btn-group d-flex mb-3">
                        <button class="btn btn-primary w-100" onclick="showSection('profile')">Profile</button>
                        <button class="btn btn-secondary w-100" onclick="showSection('password')">Password</button>
                        <button class="btn btn-dark w-100" onclick="showSection('appearance')">Appearance</button>
                    </div>

                    <!-- Profile Section -->
                    <div id="profile" class="settings-section">
                        <form method="POST" action="{{ route('settings.updateProfile') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>

                    <!-- Password Section -->
                    <div id="password" class="settings-section d-none">
                        <form method="POST" action="{{ route('settings.updatePassword') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-secondary">Save</button>
                        </form>
                    </div>

                    <!-- Appearance Section -->
                    <div id="appearance" class="settings-section d-none">
                        <form method="POST" action="{{ route('settings.updateTheme') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="theme" class="form-label">Theme</label>
                                <select class="form-control" id="theme" name="theme">
                                    <option value="light">Light</option>
                                    <option value="dark">Dark</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-dark">Apply</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showSection(section) {
        document.querySelectorAll('.settings-section').forEach(div => div.classList.add('d-none'));
        document.getElementById(section).classList.remove('d-none');
    }
</script>
@endsection
