@extends('installer.layout')

@section('content')
    <div class="text-center mb-4">
        <h2>Server Requirements</h2>
        <p>Check if your server meets the requirements for running this application.</p>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">PHP & Extension Requirements</h5>

            @foreach($requirements as $requirement => $satisfied)
                <div class="requirement-item">
                    <span>{{ $requirement }}</span>
                    @if($satisfied)
                        <i class="fas fa-check-circle text-success"></i>
                    @else
                        <i class="fas fa-times-circle text-danger"></i>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('installer.welcome') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Previous
        </a>

        @if($requirementsMet)
            <a href="{{ route('installer.permissions') }}" class="btn btn-primary">
                Next <i class="fas fa-arrow-right ms-2"></i>
            </a>
        @else
            <button class="btn btn-primary" disabled>
                Next <i class="fas fa-arrow-right ms-2"></i>
            </button>
        @endif
    </div>
@endsection