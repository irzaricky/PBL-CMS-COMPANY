@section('title', 'Daftar User Role')
@extends('InstallerEragViews::app-layout')
@section('content')
    <section class="mt-4">
        <div class="container">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="mb-4">Daftar User dengan Role</h4>
                    @if(session('account_exists'))
                        <div class="alert alert-warning mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {!! session('account_exists') !!}
                        </div>
                    @else
                        <div class="alert alert-success mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Akun Super Admin berhasil dibuat! Anda bisa login menggunakan email:
                            <strong>{{ $superAdmin->email }}</strong>
                        </div>
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Password default untuk akun dummy adalah
                            <strong>password123</strong>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
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
                                                <span class="badge bg-secondary">No Role</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Data di atas adalah daftar user yang memiliki role dalam sistem.
                    </div>
                </div>
                <div class="card-footer text-end">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('super_admin_config') }}" class="btn btn-primary">Kembali</a>
                        <a href="{{ route('feature_toggles') }}" class="btn btn-primary">Lanjut ke Konfigurasi Fitur</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection