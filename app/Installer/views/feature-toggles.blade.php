@section('title', __('installer.features_title'))
@extends('InstallerEragViews::app-layout')
@section('content')
    <section class="mt-4 installer-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="{{ route('saveFeatureToggles') }}" method="post" class="card mb-4">
                        @csrf
                        <div class="card-header bg-light">
                            <h4 class="mb-1">{{ __('installer.features_title') }}</h4>
                            <p class="text-muted mb-0 small">{{ __('installer.features_subtitle') }}</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- Feature switches in two columns --}}
                                @if($errors->any())
                                    <div class="alert alert-danger mb-4 col-12">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @foreach ($features as $feature)
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="feature_{{ $feature['key'] }}" name="features[{{ $feature['key'] }}]"
                                                value="1" {{ $feature['status_aktif'] ? 'checked' : '' }}>
                                            <label class="form-check-label" for="feature_{{ $feature['key'] }}">
                                                {{ $feature['label'] }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('user_roles_list') }}"
                                    class="btn btn-primary px-4">{{ __('installer.back') }}</a>
                                <button type="submit" id="next_button"
                                    class="btn btn-primary px-4">{{ __('installer.next') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection