@section('title', __('installer.company_title'))
@extends('InstallerEragViews::app-layout')
@section('content')    <section class="mt-4 installer-content bg-radial-gradient">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card mb-4 shadow-lg border-0 company-config-card">
                        <div class="card-body py-5">
                            <!-- Header Section with Logo and Title -->
                            <div class="text-center mb-5">
                                <!-- Company Icon -->
                                <div class="mb-4">
                                    <i class="bi bi-building display-1" style="color: var(--primary-color);"></i>
                                </div>
                                <!-- Title -->
                                <h1 class="display-5 mb-3 company-title" style="color: var(--primary-color);">
                                    {{ __('installer.company_title') }}
                                </h1>
                                <p class="lead mb-0 text-muted company-subtitle">
                                    {{ __('installer.features.company.description') }}
                                </p>
                            </div>

                            <!-- Alerts Section -->
                            @if(session('database_error'))
                                <div class="alert alert-danger mb-4">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <strong>{{ __('installer.error') }}</strong>
                                    <p class="mb-2">{{ session('database_error') }}</p>
                                    <a href="{{ route('database_import') }}" class="btn btn-sm btn-danger">
                                        {{ __('installer.back') }}
                                    </a>
                                </div>
                            @endif

                            <!-- Form Section -->
                            <form action="{{ route('saveProfilPerusahaan') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="mb-4">
                                            <x-install-input label="{{ __('installer.company_name') }}" required="true"
                                                name="nama_perusahaan" type="text" value="{{ old('nama_perusahaan') }}" />
                                            <x-install-error for="nama_perusahaan" />
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">{{ __('installer.company_logo') }}</label>
                                            <input type="file" class="form-control" name="logo_perusahaan"
                                                accept="image/jpeg,image/png,image/jpg,image/webp">
                                            <small
                                                class="form-text text-muted">{{ __('installer.logo_requirements') }}</small>
                                            <x-install-error for="logo_perusahaan" />
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">{{ __('installer.company_description') }}</label>
                                            <textarea name="deskripsi_perusahaan" class="form-control"
                                                rows="3">{{ old('deskripsi_perusahaan') }}</textarea>
                                            <x-install-error for="deskripsi_perusahaan" />
                                        </div>

                                        <div class="mb-4">
                                            <x-install-input label="{{ __('installer.company_address') }}" required="true"
                                                name="alamat_perusahaan" type="text"
                                                value="{{ old('alamat_perusahaan') }}" />
                                            <x-install-error for="alamat_perusahaan" />
                                        </div>

                                        <div class="mb-4">
                                            <x-install-input label="{{ __('installer.company_location_link') }}"
                                                name="link_alamat_perusahaan" type="text"
                                                value="{{ old('link_alamat_perusahaan') }}" />
                                            <x-install-error for="link_alamat_perusahaan" />
                                        </div>

                                        <div class="mb-5">
                                            <x-install-input label="{{ __('installer.company_email') }}" required="true"
                                                name="email_perusahaan" type="email"
                                                value="{{ old('email_perusahaan') }}" />
                                            <x-install-error for="email_perusahaan" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                                    <a href="{{ route('database_import') }}" class="btn btn-outline-primary btn-lg px-5">
                                        <i class="bi bi-arrow-left me-2"></i>
                                        {{ __('installer.back') }}
                                    </a>
                                    <button type="submit" id="next_button" class="btn btn-primary btn-lg px-5 company-btn">
                                        <i class="bi bi-building-check me-2"></i>
                                        {{ __('installer.save') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Company config page specific styles - matching finish page */
        .company-config-card {
            background: linear-gradient(135deg, #fff 0%, #f8f9ff 100%);
            border-radius: 20px !important;
            overflow: hidden;
            animation: slideInUp 0.8s ease-out;
        }

        .company-title {
            font-weight: 700;
            animation: fadeInDown 1s ease-out 0.3s both;
        }

        .company-subtitle {
            animation: fadeInUp 1s ease-out 0.5s both;
        }

        .company-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, #4338ca 100%);
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .company-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
        }

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

        /* Form styling improvements */
        .form-control {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
    </style>
@endsection