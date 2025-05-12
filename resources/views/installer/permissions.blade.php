@extends('installer.layout')

@section('content')
    <div class="text-center mb-4">
        <h2>Directory Permissions</h2>
        <p>Check if the application's directories are writable.</p>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Directory Permissions Check</h5>

            @foreach($permissions as $directory => $isWritable)
                <div class="permission-item">
                    <span>{{ $directory }}</span>
                    @if($isWritable)
                        <i class="fas fa-check-circle text-success"></i>
                    @else
                        <i class="fas fa-times-circle text-danger"></i>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('installer.requirements') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Previous
        </a>

        @if($permissionsMet)
            <a href="{{ route('installer.environment') }}" class="btn btn-primary">
                Next <i class="fas fa-arrow-right ms-2"></i>
            </a>
        @else
            <button class="btn btn-primary" disabled>
                Next <i class="fas fa-arrow-right ms-2"></i>
            </button>
        @endif
    </div>
@endsection