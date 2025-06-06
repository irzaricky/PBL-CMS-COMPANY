@extends('InstallerEragViews::app-layout')

@section('title', 'Welcome to Installation')

@section('content')
    <style>
        /* Apply Plus Jakarta Sans font to all elements */
        .installer-welcome * {
            font-family: "Plus Jakarta Sans", sans-serif !important;
        }

        .fade-in {
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            transition: transform 0.3s ease;
        }

        .card-hover:hover .feature-icon {
            transform: scale(1.1);
        }

        /* Typography improvements with Plus Jakarta Sans */
        .welcome-title {
            font-family: "Plus Jakarta Sans", sans-serif !important;
            font-weight: 700 !important;
            letter-spacing: -0.5px;
        }

        .welcome-subtitle {
            font-family: "Plus Jakarta Sans", sans-serif !important;
            font-weight: 600 !important;
        }

        .welcome-text {
            font-family: "Plus Jakarta Sans", sans-serif !important;
            font-weight: 400 !important;
        }

        .card-title {
            font-family: "Plus Jakarta Sans", sans-serif !important;
            font-weight: 600 !important;
        }

        .card-text {
            font-family: "Plus Jakarta Sans", sans-serif !important;
            font-weight: 400 !important;
        }
    </style>
    <section class="mt-4 bg-radial-gradient installer-welcome">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card mb-4 fade-in">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-gear-fill display-1" style="color: var(--primary-color);"></i>
                            </div>
                            <h1 class="display-4 mb-4 welcome-title" style="color: var(--primary-color);">Welcome to CMS
                                Company Installation</h1>
                            <p class="lead mb-4 welcome-text">
                                Thank you for choosing our Content Management System. This installation wizard will guide
                                you through the setup process to get your website up and running quickly.
                            </p>

                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <h3 class="mb-3 welcome-subtitle" style="color: var(--secondary-color);">What to Expect
                                        During Installation</h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card border-primary h-100 card-hover">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <i class="bi bi-check-circle-fill display-6 text-success feature-icon"></i>
                                            </div>
                                            <h5 class="card-title">System Requirements Check</h5>
                                            <p class="card-text text-muted">
                                                We'll verify that your server meets all the necessary requirements including
                                                PHP version, extensions, and folder permissions.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card border-primary h-100 card-hover">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <i class="bi bi-database-fill display-6 text-info feature-icon"></i>
                                            </div>
                                            <h5 class="card-title">Database Configuration</h5>
                                            <p class="card-text text-muted">
                                                Set up your database connection, configure email settings, and establish the
                                                foundation for your CMS.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card border-primary h-100 card-hover">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <i class="bi bi-building display-6 text-warning feature-icon"></i>
                                            </div>
                                            <h5 class="card-title">Company Profile Setup</h5>
                                            <p class="card-text text-muted">
                                                Configure your company information including name, logo, description, and
                                                contact details that will appear on your website.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card border-primary h-100 card-hover">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <i class="bi bi-person-gear display-6 text-danger feature-icon"></i>
                                            </div>
                                            <h5 class="card-title">Administrator Account</h5>
                                            <p class="card-text text-muted">
                                                Create your super administrator account to manage your CMS, including user
                                                roles and permissions setup.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card border-primary h-100 card-hover">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <i class="bi bi-toggles display-6 feature-icon"
                                                    style="color: var(--primary-color);"></i>
                                            </div>
                                            <h5 class="card-title">Feature Configuration</h5>
                                            <p class="card-text text-muted">
                                                Choose which modules and features to enable for your website, such as
                                                articles, products, gallery, and events.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card border-primary h-100 card-hover">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <i class="bi bi-rocket-takeoff display-6 text-success feature-icon"></i>
                                            </div>
                                            <h5 class="card-title">Launch Your Website</h5>
                                            <p class="card-text text-muted">
                                                Complete the installation and launch your fully configured CMS, ready to
                                                start creating and managing content.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="alert alert-info" role="alert">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        <strong>Installation Time:</strong> This process typically takes 5-10 minutes to
                                        complete.
                                        Make sure you have your database credentials and company information ready.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <form action="{{ route('welcome_continue') }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="bi bi-arrow-right me-2"></i>Start Installation
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection