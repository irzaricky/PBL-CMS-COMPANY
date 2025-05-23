@section('title', 'Konfigurasi Super Admin')
@extends('InstallerEragViews::app-layout')
@section('content')
    <section class="mt-4">
        <div class="container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('saveSuperAdmin') }}" method="post" class="card">
                @csrf
                <div class="card-body">
                    <div class="tab">
                        <h4 class="mb-4">Konfigurasi Akun Super Admin</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-install-input label="Nama Lengkap" required="true" name="name" type="text"
                                    value="{{ old('name') }}" />
                                <x-install-error for="name" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-install-input label="Email" required="true" name="email" type="email"
                                    value="{{ old('email') }}" />
                                <x-install-error for="email" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-install-input label="Password" required="true" name="password" type="password" />
                                <x-install-error for="password" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-install-input label="Konfirmasi Password" required="true" name="password_confirmation"
                                    type="password" />
                                <x-install-error for="password_confirmation" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('profil_perusahaan') }}" class="btn btn-outline-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Selanjutnya</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection