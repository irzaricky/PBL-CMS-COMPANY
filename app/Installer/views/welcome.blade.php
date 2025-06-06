@extends('InstallerEragViews::app-layout')

@section('title', __('installer.welcome_title'))

@section('content')

    <section class="mt-4 bg-radial-gradient installer-welcome">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card mb-4 fade-in">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-gear-fill display-1" style="color: var(--primary-color);"></i>
                            </div>
                            <h1 class="display-4 mb-4 welcome-title" style="color: var(--primary-color);">
                                {{ __('installer.welcome_title') }}
                            </h1>
                            <p class="lead mb-4 welcome-text">
                                {{ __('installer.welcome_subtitle') }}
                            </p>

                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <h3 class="mb-3 welcome-subtitle" style="color: var(--secondary-color);">
                                        {{ __('installer.installation_process') }}
                                    </h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card border-primary h-100 card-hover">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <i class="bi bi-check-circle-fill display-6 text-success feature-icon"></i>
                                            </div>
                                            <h5 class="card-title">{{ __('installer.features.requirements.title') }}</h5>
                                            <p class="card-text text-muted">
                                                {{ __('installer.features.requirements.description') }}
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
                                            <h5 class="card-title">{{ __('installer.features.database.title') }}</h5>
                                            <p class="card-text text-muted">
                                                {{ __('installer.features.database.description') }}
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
                                            <h5 class="card-title">{{ __('installer.features.company.title') }}</h5>
                                            <p class="card-text text-muted">
                                                {{ __('installer.features.company.description') }}
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
                                            <h5 class="card-title">{{ __('installer.features.admin.title') }}</h5>
                                            <p class="card-text text-muted">
                                                {{ __('installer.features.admin.description') }}
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
                                            <h5 class="card-title">{{ __('installer.features.features.title') }}</h5>
                                            <p class="card-text text-muted">
                                                {{ __('installer.features.features.description') }}
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
                                            <h5 class="card-title">{{ __('installer.features.complete.title') }}</h5>
                                            <p class="card-text text-muted">
                                                {{ __('installer.features.complete.description') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-md-12">
                                    <div class="alert alert-info" role="alert">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        <strong>{{ __('installer.installation_time') }}:</strong>
                                        {{ __('installer.installation_time_description') }}
                                        {{ __('installer.preparation_note') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <form action="{{ route('welcome_continue') }}" method="post" class="mb-0">
                                @csrf <button type="submit" class="btn btn-primary px-4">
                                    {{ __('installer.get_started') }} <i class="bi bi-arrow-right me-2"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection