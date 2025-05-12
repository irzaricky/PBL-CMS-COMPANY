@extends('installer.layout')

@section('content') <div class="text-center mb-4">
        <h2>Database Setup</h2>
        <p>Now we'll set up your database structure automatically.</p>
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

            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Database connection successful!</strong> Your database credentials are working correctly.
            </div>
            <div class="text-center my-4">
                <i class="fas fa-database fa-3x text-primary mb-3"></i>
                <h5>Ready to Create Database Tables</h5>
                <p>Click the button below to set up all necessary tables for your website. This should take just a few
                    moments.</p>
            </div>

            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Warning:</strong> This process will create all the necessary database tables for your application.
                If the database already contains tables with the same names, they will be replaced and all existing data
                will be lost.
            </div>

            <div class="form-check mb-4 ps-5">
                <input class="form-check-input" type="checkbox" id="confirm-migrate" onchange="toggleMigrateButton()">
                <label class="form-check-label" for="confirm-migrate">
                    I understand that this process will create new tables and any existing data with the same table names
                    will be lost.
                </label>
            </div>

            <div class="d-grid gap-2 col-md-8 mx-auto my-4">
                <a href="{{ route('installer.database.run') }}" class="btn btn-primary btn-lg" id="migrate-button" disabled>
                    <i class="fas fa-cogs me-2"></i> Create Database Tables
                </a>
            </div>

            <script>
                function toggleMigrateButton() {
                    document.getElementById('migrate-button').disabled = !document.getElementById('confirm-migrate').checked;
                }
            </script>
        </div>
    </div>

    <div class="d-flex justify-content-start">
        <a href="{{ route('installer.environment') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Previous
        </a>
    </div>
@endsection