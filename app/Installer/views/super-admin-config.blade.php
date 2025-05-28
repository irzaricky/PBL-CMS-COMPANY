@section('title', 'Konfigurasi Super Admin')
@extends('InstallerEragViews::app-layout')
@section('content')
    <section class="mt-4">
        <div class="container">
            @if (session('account_exists'))
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('account_exists') }}
                </div>
            @endif
            @if (session('database_error'))
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('database_error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-md-8 cs_center">
                <form action="{{ route('saveSuperAdmin') }}" method="post" class="card">
                    @csrf
                    <div class="card-body">
                        <div class="tab">
                            <h4 class="mb-4">Konfigurasi Akun Super Admin</h4>
                            <!-- single column inputs -->
                            <div class="col-md-12 mb-3">
                                <x-install-input label="Nama Lengkap" required="true" name="name" type="text"
                                    value="{{ old('name') }}" />
                                <x-install-error for="name" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <x-install-input label="Email" required="true" name="email" type="email"
                                    value="{{ old('email') }}" />
                                <x-install-error for="email" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="mb-1" for="password">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                        data-target="password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">The password field must be at least 8
                                    characters.</small>
                                <x-install-error for="password" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="mb-1" for="password_confirmation">Konfirmasi Password <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                        data-target="password_confirmation">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <x-install-error for="password_confirmation" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="include_dummy_data" id="include_dummy_data"
                                        class="form-check-input" value="1" {{ old('include_dummy_data') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="include_dummy_data">
                                        <strong>Isi dengan data dummy</strong>
                                    </label>
                                    <div class="form-text text-muted">
                                        Centang opsi ini jika Anda ingin mengisi database dengan data contoh untuk keperluan
                                        testing atau demo.
                                        Data dummy termasuk artikel, produk, galeri, dan konten lainnya.
                                    </div>
                                </div>
                            </div>
                            <!-- end single column inputs -->
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profil_perusahaan') }}" class="btn btn-primary px-4">Kembali</a>
                            <button type="submit" class="btn btn-primary px-4">Selanjutnya</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </section>
@endsection