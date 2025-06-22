@section('title', __('installer.finish_title'))
@extends('InstallerEragViews::app-layout')
@section('content')
    <section class="mt-4 installer-content bg-radial-gradient">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card mb-4 shadow-lg border-0 finish-card">
                        <div class="card-body text-center py-5">
                            <!-- Success Animation Icon -->
                            <div class="mb-4 success-animation">
                                <div class="success-checkmark">
                                    <div class="check-icon">
                                        <span class="icon-line line-tip"></span>
                                        <span class="icon-line line-long"></span>
                                        <div class="icon-circle"></div>
                                        <div class="icon-fix"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Title and Subtitle -->
                            <h1 class="display-5 mb-3 finish-title" style="color: var(--primary-color);">
                                {{ __('installer.finish_title') }}
                            </h1>
                            <p class="lead mb-5 text-muted finish-subtitle">
                                {{ __('installer.finish_subtitle') }}
                            </p>

                            <!-- Installation Summary Cards -->
                            <div class="row mb-5">
                                <div class="col-md-4 mb-3">
                                    <div class="feature-card h-100">
                                        <div class="feature-icon">
                                            <i class="bi bi-database-check text-success"></i>
                                        </div>
                                        <h6 class="mb-2">{{ __('installer.database_configured') }}</h6>
                                        <small class="text-muted">{{ __('installer.database_ready') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="feature-card h-100">
                                        <div class="feature-icon">
                                            <i class="bi bi-person-check text-info"></i>
                                        </div>
                                        <h6 class="mb-2">{{ __('installer.admin_created') }}</h6>
                                        <small class="text-muted">{{ __('installer.admin_ready') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="feature-card h-100">
                                        <div class="feature-icon">
                                            <i class="bi bi-gear-fill text-warning"></i>
                                        </div>
                                        <h6 class="mb-2">{{ __('installer.system_configured') }}</h6>
                                        <small class="text-muted">{{ __('installer.system_ready') }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                                <a href="{{ route('finishSave') }}" class="btn btn-primary btn-lg px-5 finish-btn">
                                    <i class="bi bi-check-circle me-2"></i>
                                    {{ __('installer.finalize_installation') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Finish page specific styles */
        .finish-card {
            background: linear-gradient(135deg, #fff 0%, #f8f9ff 100%);
            border-radius: 20px !important;
            overflow: hidden;
            animation: slideInUp 0.8s ease-out;
        }

        .finish-title {
            font-weight: 700;
            animation: fadeInDown 1s ease-out 0.3s both;
        }

        .finish-subtitle {
            animation: fadeInUp 1s ease-out 0.5s both;
        }

        /* Success checkmark animation */
        .success-animation {
            animation: zoomIn 0.8s ease-out 0.2s both;
        }

        .success-checkmark {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: block;
            stroke-width: 2;
            stroke: var(--primary-color);
            stroke-miterlimit: 10;
            margin: 10px auto;
            box-shadow: inset 0px 0px 0px var(--primary-color);
            animation: fill 0.4s ease-in-out 0.4s forwards, scale 0.3s ease-in-out 0.9s both;
            position: relative;
        }

        .success-checkmark .check-icon {
            width: 56px;
            height: 56px;
            position: absolute;
            left: 32px;
            top: 32px;
            border-radius: 50%;
            display: block;
            stroke-width: 3;
            stroke: #fff;
            stroke-miterlimit: 10;
            margin: 10% auto;
            box-shadow: inset 0px 0px 0px var(--primary-color);
            animation: fill 0.4s ease-in-out 0.4s forwards, scale 0.3s ease-in-out 0.9s both;
        }

        .success-checkmark .check-icon::after {
            content: '';
            width: 5px;
            height: 3px;
            border-radius: 2px;
            position: absolute;
            z-index: 10;
            background: var(--primary-color);
            left: 9px;
            top: 6px;
            animation: fill 0.4s ease-in-out 0.4s forwards, scale 0.3s ease-in-out 0.9s both;
        }

        .success-checkmark .icon-line {
            height: 4px;
            background: #fff;
            display: block;
            border-radius: 2px;
            position: absolute;
            z-index: 10;
        }

        .success-checkmark .icon-line.line-tip {
            top: 46px;
            left: 14px;
            width: 25px;
            transform: rotate(45deg);
            animation: icon-line-tip 0.75s;
        }

        .success-checkmark .icon-line.line-long {
            top: 38px;
            right: 8px;
            width: 47px;
            transform: rotate(-45deg);
            animation: icon-line-long 0.75s;
        }

        .success-checkmark .icon-circle {
            top: -4px;
            left: -4px;
            z-index: 10;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            position: absolute;
            box-sizing: content-box;
            border: 4px solid var(--primary-color);
        }

        .success-checkmark .icon-fix {
            top: 8px;
            width: 5px;
            left: 26px;
            z-index: 1;
            height: 85px;
            position: absolute;
            transform: rotate(-45deg);
            background: #fff;
        }

        /* Feature cards */
        .feature-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .feature-card h6 {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Next steps alert */
        .next-steps-alert {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%) !important;
            border-radius: 15px;
        }

        /* Finish button */
        .finish-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .finish-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
        }

        /* Animations */
        @keyframes slideInUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.5);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes fill {
            100% {
                box-shadow: inset 0px 0px 0px 60px var(--primary-color);
            }
        }

        @keyframes scale {

            0%,
            100% {
                transform: none;
            }

            50% {
                transform: scale3d(1.1, 1.1, 1);
            }
        }

        @keyframes icon-line-tip {
            0% {
                width: 0;
                left: 1px;
                top: 19px;
            }

            54% {
                width: 0;
                left: 1px;
                top: 19px;
            }

            70% {
                width: 50px;
                left: -8px;
                top: 37px;
            }

            84% {
                width: 17px;
                left: 21px;
                top: 48px;
            }

            100% {
                width: 25px;
                left: 14px;
                top: 45px;
            }
        }

        @keyframes icon-line-long {
            0% {
                width: 0;
                right: 46px;
                top: 54px;
            }

            65% {
                width: 0;
                right: 46px;
                top: 54px;
            }

            84% {
                width: 55px;
                right: 0px;
                top: 35px;
            }

            100% {
                width: 47px;
                right: 8px;
                top: 38px;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .finish-card .card-body {
                padding: 2rem 1rem;
            }

            .success-checkmark {
                width: 80px;
                height: 80px;
            }

            .success-checkmark .check-icon {
                width: 36px;
                height: 36px;
                left: 22px;
                top: 22px;
            }

            .success-checkmark .icon-circle {
                width: 80px;
                height: 80px;
            }

            .feature-card {
                margin-bottom: 1rem;
            }
        }
    </style>
@endsection