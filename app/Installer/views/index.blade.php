@section('title', __('installer.requirements_title'))
@extends('InstallerEragViews::app-layout')
@section('content')
    <section class="mt-4 bg-radial-gradient installer-content">
        <div class="container">
            <form action="{{ route('install_check') }}" method="post">
                @csrf
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
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
                                                <td>{{ Str::upper($type) }}</td>
                                                <td>{{ $extension }}</td>
                                                <td>
                                                    <span class="badge text-bg-{{ $enabled ? 'success' : 'danger' }}">
                                                        {{ $enabled ? __('installer.supported') : __('installer.not_supported') }}
                                                        <i class="bi bi-{{ $enabled ? 'bi bi-check-circle' : 'x-circle' }}"></i>
                                                    </span>
                                                </td>
                                                <td>{{__('installer.version')}} {{ $phpSupportInfo['current'] }}
                                                    <i
                                                        class="text-{{ $phpSupportInfo['supported'] ? 'success' : 'danger' }} bi bi-{{ $phpSupportInfo['supported'] ? 'check-circle-fill' : 'x-circle-fill' }}"></i>
                                                </td>
                                                <td>({{__('installer.version')}} {{ $phpSupportInfo['minimum'] }}
                                                    {{ __('installer.required') }})
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>


                <div class="card mb-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('installer.folder') }}</th>
                                        <th>{{ __('installer.feature_status') }}</th>
                                        <th>{{ __('installer.folder_permissions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions['permissions'] as $permission)
                                        <tr>
                                            <td>{{ $permission['folder'] }}</td>
                                            <td>
                                                <span class="badge text-bg-{!! $permission['isSet'] ? 'success' : 'danger' !!}">
                                                    {!! $permission['isSet'] ? __('installer.writable') : __('installer.not_writable') !!}
                                                    <i
                                                        class="bi bi-{{ $permission['isSet'] ? 'bi bi-check-circle' : 'x-circle' }}"></i>
                                                </span>
                                            </td>
                                            <td>{{ $permission['permission'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if (!isset($requirements['errors']) && $phpSupportInfo['supported'])
                    @if (!isset($permissions['errors']))
                        <div class="card-footer footerHome text-end">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('welcome') }}"
                                    class="btn btn-primary me-auto ms-3 px-4">{{ __('installer.back') }}</a>
                                <button type="submit" id="next_button"
                                    class="btn btn-primary px-4">{{ __('installer.next') }}</button>
                            </div>
                        </div>
                    @endif
                @endif
            </form>
        </div>
    </section>
@endsection