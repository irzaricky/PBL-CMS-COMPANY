@section('title', __('installer.requirements_title'))
@extends('InstallerEragViews::app-layout')
@section('content')
    <section class="mt-4 bg-radial-gradient installer-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card mb-4 shadow-lg border-0 requirements-config-card">
                        <div class="card-body py-5">
                            <!-- Header Section with Icon and Title -->
                            <div class="text-center mb-5">
                                <div class="mb-4">
                                    <i class="bi bi-gear-fill display-1" style="color: var(--primary-color);"></i>
                                </div>
                                <h1 class="display-5 mb-3 requirements-title" style="color: var(--primary-color);">
                                    {{ __('installer.requirements_title') }}
                                </h1>
                                <p class="lead mb-0 text-muted requirements-subtitle">
                                    {{ __('installer.requirements_subtitle') }}
                                </p>
                            </div>

                            <!-- Requirements Content -->
                            <form action="{{ route('install_check') }}" method="post">
                                @csrf

                                <!-- PHP Extensions & Requirements Section -->
                                <div class="mb-5">
                                    <h4 class="mb-3 text-center" style="color: var(--primary-color);">
                                        <i class="bi bi-code-square me-2"></i>{{ __('installer.php_requirements') }}
                                    </h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover modern-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('installer.software_type') }}</th>
                                                    <th>{{ __('installer.php_extensions') }}</th>
                                                    <th>{{ __('installer.feature_status') }}</th>
                                                    <th>{{ __('installer.version') }}</th>
                                                    <th>{{ __('installer.server_requirements') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($requirements['requirements'] as $type => $requirement)
                                                    @foreach ($requirements['requirements'][$type] as $extension => $enabled)
                                                        <tr>
                                                            <td><span class="fw-medium">{{ Str::upper($type) }}</span></td>
                                                            <td>{{ $extension }}</td>
                                                            <td>
                                                                <span
                                                                    class="badge rounded-pill text-bg-{{ $enabled ? 'success' : 'danger' }}">
                                                                    {{ $enabled ? __('installer.supported') : __('installer.not_supported') }}
                                                                    <i
                                                                        class="bi bi-{{ $enabled ? 'check-circle' : 'x-circle' }} ms-1"></i>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span class="fw-medium">{{ __('installer.version') }}
                                                                    {{ $phpSupportInfo['current'] }}</span>
                                                                <i
                                                                    class="text-{{ $phpSupportInfo['supported'] ? 'success' : 'danger' }} bi bi-{{ $phpSupportInfo['supported'] ? 'check-circle-fill' : 'x-circle-fill' }} ms-1"></i>
                                                            </td>
                                                            <td class="text-muted">
                                                                ({{ __('installer.version') }} {{ $phpSupportInfo['minimum'] }}
                                                                {{ __('installer.required') }})
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Folder Permissions Section -->
                                <div class="mb-5">
                                    <h4 class="mb-3 text-center" style="color: var(--primary-color);">
                                        <i class="bi bi-folder-check me-2"></i>{{ __('installer.folder_permissions') }}
                                    </h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover modern-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ __('installer.folder') }}</th>
                                                    <th>{{ __('installer.feature_status') }}</th>
                                                    <th>{{ __('installer.folder_permissions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($permissions['permissions'] as $permission)
                                                    <tr>
                                                        <td><code
                                                                class="bg-light px-2 py-1 rounded">{{ $permission['folder'] }}</code>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge rounded-pill text-bg-{!! $permission['isSet'] ? 'success' : 'danger' !!}">
                                                                {!! $permission['isSet'] ? __('installer.writable') : __('installer.not_writable') !!}
                                                                <i
                                                                    class="bi bi-{{ $permission['isSet'] ? 'check-circle' : 'x-circle' }} ms-1"></i>
                                                            </span>
                                                        </td>
                                                        <td><span class="fw-medium">{{ $permission['permission'] }}</span></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                @if (!isset($requirements['errors']) && $phpSupportInfo['supported'])
                                    @if (!isset($permissions['errors']))
                                        <div class="d-grid gap-3 d-md-flex justify-content-md-center mt-4">
                                            <a href="{{ route('welcome') }}" class="btn btn-outline-primary btn-lg px-5 me-md-3">
                                                <i class="bi bi-arrow-left me-2"></i>{{ __('installer.back') }}
                                            </a>
                                            <button type="submit" id="next_button"
                                                class="btn btn-primary btn-lg px-5 requirements-btn">
                                                <i class="bi bi-check-circle me-2"></i>{{ __('installer.next') }}
                                            </button>
                                        </div>
                                    @endif
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Requirements page specific styles - matching unified design */
        .requirements-config-card {
            background: linear-gradient(135deg, #fff 0%, #f8f9ff 100%);
            border-radius: 20px !important;
            overflow: hidden;
            animation: slideInUp 0.8s ease-out;
        }

        .requirements-title {
            font-weight: 700;
            animation: fadeInDown 1s ease-out 0.3s both;
        }

        .requirements-subtitle {
            animation: fadeInUp 1s ease-out 0.5s both;
        }

        .requirements-btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, #4338ca 100%);
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .requirements-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
        }

        /* Modern table styling */
        .modern-table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .modern-table thead th {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            font-weight: 600;
            color: #495057;
            padding: 1rem;
        }

        .modern-table tbody tr {
            transition: all 0.3s ease;
        }

        .modern-table tbody tr:hover {
            background-color: rgba(99, 102, 241, 0.05);
            transform: translateX(2px);
        }

        .modern-table tbody td {
            padding: 1rem;
            border-color: #e9ecef;
            vertical-align: middle;
        }

        /* Badge styling */
        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            font-weight: 500;
        }

        /* Code styling */
        code {
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Button styling */
        .btn-outline-primary {
            border-radius: 10px;
            border-width: 2px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
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

        /* Section headers */
        h4 {
            font-weight: 600;
            position: relative;
        }

        h4::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, var(--primary-color) 0%, #4338ca 100%);
            border-radius: 2px;
        }
    </style>
@endsection