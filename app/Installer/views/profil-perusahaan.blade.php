@section('title', 'Profil Perusahaan')
@extends('InstallerEragViews::app-layout')
@section('content')
    <section class="mt-4">
        <div class="container">
            @if(session('database_error'))
                <div class="alert alert-danger mb-4">
                    <strong>Database Connection Error!</strong>
                    <p>{{ session('database_error') }}</p>
                    <a href="{{ route('database_import') }}" class="btn btn-sm btn-danger mt-2">
                        Return to Database Configuration
                    </a>
                </div>
            @endif

            <div class="col-md-8 cs_center">
                <form action="{{ route('saveProfilPerusahaan') }}" method="post" class="card" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="tab">
                            <h4 class="mb-4">Konfigurasi Profil Perusahaan</h4>

                            <div class="col-md-12 mb-3">
                                <x-install-input label="Nama Perusahaan" required="true" name="nama_perusahaan" type="text"
                                    value="{{ old('nama_perusahaan') }}" />
                                <x-install-error for="nama_perusahaan" />
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Logo Perusahaan</label>
                                <input type="file" class="form-control" name="logo_perusahaan"
                                    accept="image/jpeg,image/png,image/jpg">
                                <small class="form-text text-muted">Upload logo perusahaan (JPG, PNG, JPEG - maks.
                                    2MB)</small>
                                <x-install-error for="logo_perusahaan" />
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Deskripsi Perusahaan</label>
                                <textarea name="deskripsi_perusahaan" class="form-control"
                                    rows="3">{{ old('deskripsi_perusahaan') }}</textarea>
                                <x-install-error for="deskripsi_perusahaan" />
                            </div>

                            <div class="col-md-12 mb-3">
                                <x-install-input label="Alamat Perusahaan" required="true" name="alamat_perusahaan"
                                    type="text" value="{{ old('alamat_perusahaan') }}" />
                                <x-install-error for="alamat_perusahaan" />
                            </div>

                            <div class="col-md-12 mb-3">
                                <x-install-input label="Link Alamat Perusahaan (Google Maps)" name="link_alamat_perusahaan"
                                    type="text" value="{{ old('link_alamat_perusahaan') }}" />
                                <x-install-error for="link_alamat_perusahaan" />
                            </div>

                            <div class="col-md-12 mb-3">
                                <x-install-input label="Email Perusahaan" required="true" name="email_perusahaan"
                                    type="email" value="{{ old('email_perusahaan') }}" />
                                <x-install-error for="email_perusahaan" />
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Sejarah Perusahaan</label>
                                <textarea name="sejarah_perusahaan" class="form-control"
                                    rows="3">{{ old('sejarah_perusahaan') }}</textarea>
                                <x-install-error for="sejarah_perusahaan" />
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Visi Perusahaan</label>
                                <textarea name="visi_perusahaan" class="form-control"
                                    rows="2">{{ old('visi_perusahaan') }}</textarea>
                                <x-install-error for="visi_perusahaan" />
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Misi Perusahaan</label>
                                <textarea name="misi_perusahaan" class="form-control"
                                    rows="3">{{ old('misi_perusahaan') }}</textarea>
                                <x-install-error for="misi_perusahaan" />
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="d-flex">
                            <button type="submit" id="next_button" class="btn btn-primary ms-auto">Next</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection