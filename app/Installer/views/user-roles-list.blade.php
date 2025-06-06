@section('title', __('installer.roles_title'))
@extends('InstallerEragViews::app-layout')
@section('content')
    <section class="mt-4 installer-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h4 class="mb-4">{{ __('installer.user_roles_list') }}</h4>
                            @if(session('account_exists'))
                                <div class="alert alert-warning mb-4">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    {!! session('account_exists') !!}
                                </div>
                            @else
                                <div class="alert alert-success mb-4">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    {{ __('installer.super_admin_created') }}
                                    <strong>{{ $superAdmin->email }}</strong>
                                </div>
                                <div class="alert alert-info mb-4">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    {{ __('installer.dummy_password_info') }}
                                    <strong>password123</strong>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('installer.name') }}</th>
                                            <th>{{ __('installer.email') }}</th>
                                            <th>{{ __('installer.role') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @if($user->roles->count() > 0)
                                                        @foreach($user->roles as $role)
                                                            <span class="badge bg-primary">{{ $role->name }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="badge bg-secondary">{{ __('installer.no_role') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-0">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('super_admin_config') }}"
                                    class="btn btn-primary px-4">{{ __('installer.back') }}</a>
                                <a href="{{ route('feature_toggles') }}"
                                    class="btn btn-primary px-4">{{ __('installer.continue_to_features') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection