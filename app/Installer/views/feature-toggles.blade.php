@section('title', 'Feature Toggles')
@extends('InstallerEragViews::app-layout')
@section('content')
    <section class="mt-4">
        <div class="container">
            <div class="col-md-8 cs_center">
                <form action="{{ route('saveFeatureToggles') }}" method="post" class="card">
                    @csrf
                    <div class="card-body">
                        <div class="tab">
                            <h4 class="mb-4">Konfigurasi Feature Toggles</h4>
                            <p class="text-muted mb-4">
                                Pilih fitur mana saja yang akan diaktifkan dan ditampilkan pada frontend website.
                            </p>

                            <div class="features-container">
                                @if($errors->any())
                                    <div class="alert alert-danger mb-4">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @foreach ($features as $feature)
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="feature_{{ $feature['key'] }}" name="features[{{ $feature['key'] }}]" value="1"
                                            {{ $feature['status_aktif'] ? 'checked' : '' }}>
                                        <label class="form-check-label" for="feature_{{ $feature['key'] }}">
                                            {{ $feature['label'] }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="d-flex">
                            <a href="{{ route('profil_perusahaan') }}" class="btn btn-primary">Sebelumnya</a>
                            <button type="submit" id="next_button" class="btn btn-primary ms-auto">Next</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection