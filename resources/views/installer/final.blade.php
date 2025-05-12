@extends('installer.layout')

@section('content') <div class="text-center mb-4">
        <h2>Installation Complete!</h2>
        <p>Your company website has been successfully installed.</p>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                The installation process has been completed successfully.
            </div>

            <div class="text-center mb-3 mt-4">
                <i class="fas fa-trophy fa-4x text-success"></i>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Installation Summary</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-transparent">✅ System requirements verified</li>
                                <li class="list-group-item bg-transparent">✅ File permissions configured</li>
                                <li class="list-group-item bg-transparent">✅ Database connection established</li>
                                <li class="list-group-item bg-transparent">✅ Application migrations completed</li>
                                <li class="list-group-item bg-transparent">✅ Admin account created</li>
                                <li class="list-group-item bg-transparent">✅ Company profile initialized</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center my-4">
                <h5>What's Next?</h5>
                <p>You can now log in to your admin dashboard using the credentials you provided during setup.</p>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card h-100 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-tasks me-2"></i> First Steps</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Complete your company profile</li>
                                <li class="list-group-item">Add products to your catalog</li>
                                <li class="list-group-item">Write your first article</li>
                                <li class="list-group-item">Customize your homepage slider</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-life-ring me-2"></i> Support Resources</h5>
                        </div>
                        <div class="card-body">
                            <p>Need help getting started?</p>
                            <ul>
                                <li>Check the documentation in the <code>docs/</code> folder</li>
                                <li>Visit the admin help section</li>
                                <li>Contact support at <a href="mailto:support@pbl-cms.com">support@pbl-cms.com</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 col-6 mx-auto">
                <a href="{{ url('/admin') }}" class="btn btn-lg btn-primary">
                    <i class="fas fa-lock me-2"></i> Go to Admin Login
                </a>
                <a href="{{ url('/') }}" class="btn btn-outline-primary">
                    <i class="fas fa-home me-2"></i> View Your Website
                </a>
            </div>
        </div>
    </div>
@endsection