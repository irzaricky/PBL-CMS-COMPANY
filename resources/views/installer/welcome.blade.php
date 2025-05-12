@extends('installer.layout')

@section('content') <div class="text-center mb-4">
        <h2>Welcome to the CMS Installation Wizard</h2>
        <p>This wizard will help you set up your company website with just a few clicks.</p>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Installation Steps</h5>
            <p>We'll guide you through these simple steps:</p>

            <ul class="list-group mb-4">
                <li class="list-group-item d-flex align-items-center">
                    <i class="fas fa-server text-primary me-3"></i>
                    <div>
                        <strong>Server Requirements</strong>
                        <p class="mb-0 small text-muted">We'll check that your server meets all requirements</p>
                    </div>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <i class="fas fa-folder-open text-primary me-3"></i>
                    <div>
                        <strong>File Permissions</strong>
                        <p class="mb-0 small text-muted">We'll verify the correct permissions are set</p>
                    </div>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <i class="fas fa-database text-primary me-3"></i>
                    <div>
                        <strong>Database Setup</strong>
                        <p class="mb-0 small text-muted">Enter your database details just once</p>
                    </div>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <i class="fas fa-user-shield text-primary me-3"></i>
                    <div>
                        <strong>Admin Account</strong>
                        <p class="mb-0 small text-muted">Create your administrator account</p>
                    </div>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <i class="fas fa-building text-primary me-3"></i>
                    <div>
                        <strong>Company Profile</strong>
                        <p class="mb-0 small text-muted">Set up your company name and logo</p>
                    </div>
                </li>
            </ul>

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Make sure you have your database credentials ready before proceeding.
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <a href="{{ route('installer.requirements') }}" class="btn btn-primary">
            Check Requirements <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>
@endsection