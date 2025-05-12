@extends('installer.layout')

@section('content') <div class="text-center mb-4">
        <h2>Create Admin Account</h2>
        <p>This account will have full access to manage your website.</p>
    </div>

    <div class="card mb-4">
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

            <div class="alert alert-info mb-4">
                <i class="fas fa-shield-alt me-2"></i>
                <strong>Security tip:</strong> Use a strong password that includes uppercase, lowercase, numbers, and
                special characters.
            </div>

            <form method="POST" action="{{ route('installer.admin.save') }}">
                @csrf

                <div class="text-center mb-4">
                    <i class="fas fa-user-shield fa-3x text-primary"></i>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    <div class="form-text">You'll use this email to log in to your admin panel.</div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="form-text">Password must be at least 8 characters long.</div>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                        required>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('installer.database') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Previous
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Create Admin <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection